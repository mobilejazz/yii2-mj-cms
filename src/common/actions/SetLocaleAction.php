<?php

namespace mobilejazz\yii2\cms\common\actions;

use Codeception\Coverage\Subscriber\Local;
use mobilejazz\yii2\cms\common\models\ContentSlug;
use mobilejazz\yii2\cms\common\models\Locale;
use yii;
use yii\base\Action;
use yii\base\InvalidParamException;
use yii\web\Cookie;

/**
 * Class SetLocaleAction
 * @package common\actions\base
 *
 * Example:
 *
 *   public function actions()
 *   {
 *       return [
 *           'set-locale'=>[
 *               'class'=>'common\actions\SetLocaleAction',
 *               'locales'=>[
 *                   'en-US', 'ru-RU', 'ua-UA'
 *               ],
 *               'localeCookieName'=>'_locale',
 *               'callback'=>function($action){
 *                   return $this->controller->redirect(/.. some url ../)º
 *               }
 *           ]
 *       ];
 *   }
 */
class SetLocaleAction extends Action
{

    /**
     * @var array List of available locales
     */
    public $locales;

    /**
     * @var string
     */
    public $localeCookieName = '_locale';

    /**
     * @var integer
     */
    public $cookieExpire;

    /**
     * @var string
     */
    public $cookieDomain;

    /**
     * @var \Closure
     */
    public $callback;


    /**
     * @param $locale
     *
     * @return mixed|static
     */
    public function run($locale)
    {
        if (!is_array($this->locales) || !in_array($locale, $this->locales, true)) {
            throw new InvalidParamException('Unacceptable locale');
        }

        $cookie = new Cookie([
            'name' => $this->localeCookieName,
            'value' => $locale,
            'expire' => $this->cookieExpire ?: time() + 60 * 60 * 24 * 365,
            'domain' => $this->cookieDomain ?: '',
        ]);

        Yii::$app->getResponse()
            ->getCookies()
            ->add($cookie);

        if ($this->callback && $this->callback instanceof \Closure) {
            return call_user_func_array($this->callback, [
                $this,
                $locale
            ]);
        }

        $referrer = Yii::$app->request->referrer;

        if ($referrer) {

            $matches = [];
            $currentLocale = preg_match('/\/([a-z]{2}_[a-z]{2})\/.*/', $referrer, $matches) == 1 ? $matches[1] : null;

            if (Locale::isLocaleUsed($currentLocale)) {

                $slugStr = substr($referrer, strpos($referrer, $currentLocale) + 6);
                $currentSlug = ContentSlug::findOne(['slug' => $slugStr, 'language' => $currentLocale]);

                if ($currentSlug) {
                    $newSlug = ContentSlug::findOne(['content_id' => $currentSlug->content_id, 'language' => $locale]);

                    Yii::$app->response->redirect("/$locale/" . $newSlug->slug);
                }

            } else {

                foreach ($this->locales as $configuredLocale) {
                    $count = 0;
                    $referrer = str_replace($configuredLocale, $locale, $referrer, $count);
                    if ($count > 0) break;
                }

                Yii::$app->response->redirect($referrer ? $referrer : Yii::$app->homeUrl);
            }

        }

    }
}
