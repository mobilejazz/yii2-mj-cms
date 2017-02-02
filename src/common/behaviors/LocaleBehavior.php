<?php

namespace mobilejazz\yii2\cms\common\behaviors;

use mobilejazz\yii2\cms\common\models\Locale;
use Yii;
use yii\base\Behavior;
use yii\web\Application;

/**
 * Class LocaleBehavior
 * @package common\behaviors
 */
class LocaleBehavior extends Behavior
{

    /**
     * @var string
     */
    public $cookieName = '_locale';

    /**
     * @var bool
     */
    public $enablePreferredLanguage = true;


    /**
     * Resolve application language by checking request path, user cookies, preferred language and profile settings
     */
    public function beforeRequest()
    {

        $request = Yii::$app->getRequest();
        $pathInfo = $request->getPathInfo();

        $availableLocales = $this->getAvailableLocales(true);

        $matches = [];

        if(preg_match('/^([a-z]{2}_[a-z]{2})\/.*/', $pathInfo, $matches) == 1 && in_array($matches[1], $availableLocales)){

            $userLocale = $matches[1];

        } else if (Yii::$app->getRequest()
                     ->getCookies()
                     ->has($this->cookieName) && !Yii::$app->session->hasFlash('forceUpdateLocale')
        )
        {
            $userLocale = Yii::$app->getRequest()
                                   ->getCookies()
                                   ->getValue($this->cookieName);
        }
        else
        {
            $userLocale = str_replace('-', '_', Yii::$app->language);

            if ($this->enablePreferredLanguage)
            {
                $userLocale = Yii::$app->request->getPreferredLanguage($this->getAvailableLocales(true));
            }
        }

        if (!Locale::isLocaleUsed($userLocale))
        {
            $userLocale = Locale::getIdentifier(Locale::getDefault());
        }

        Yii::info("User locale = $userLocale");

        Yii::$app->language = $userLocale;
    }


    /**
     * @param bool $only_used
     *
     * @return array
     */
    protected function getAvailableLocales($only_used = false)
    {
        return array_keys(Locale::getAllLocalesAsMap($only_used));
    }


    /**
     * @return array
     */
    public function events()
    {
        return [
            Application::EVENT_BEFORE_REQUEST => 'beforeRequest',
        ];
    }
}
