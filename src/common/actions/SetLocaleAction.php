<?php

namespace mobilejazz\yii2\cms\common\actions;

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
        if (!is_array($this->locales) || !in_array($locale, $this->locales, true))
        {
            throw new InvalidParamException('Unacceptable locale');
        }
        $cookie = new Cookie([
            'name'   => $this->localeCookieName,
            'value'  => $locale,
            'expire' => $this->cookieExpire ?: time() + 60 * 60 * 24 * 365,
            'domain' => $this->cookieDomain ?: '',
        ]);
        Yii::$app->getResponse()
                 ->getCookies()
                 ->add($cookie);
        if ($this->callback && $this->callback instanceof \Closure)
        {
            return call_user_func_array($this->callback, [
                $this,
                $locale
            ]);
        }

        $referrer = Yii::$app->request->referrer;

        if($referrer){
            foreach($this->locales as $configuredLocale){
                $count = 0;
                $referrer = str_replace($configuredLocale, $locale, $referrer, $count);
                if($count > 0) break;
            }
        }

        return Yii::$app->response->redirect($referrer ? $referrer : Yii::$app->homeUrl);
    }
}
