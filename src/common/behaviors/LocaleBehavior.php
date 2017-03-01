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

        // look for locale in path

        $matches = [];
        $localeFromPath = preg_match('/^([a-z]{2}_[a-z]{2})\/.*/', $pathInfo, $matches) == 1 ? $matches[1] : null;

        $localeFromCookie = $this->enablePreferredLanguage && $request->getCookies()->has($this->cookieName) ? $request->getCookies()->getValue($this->cookieName) : null;

        if(isset($localeFromPath)){
            $userLocale = $matches[1];
        } else if(isset($localeFromCookie)){
            $userLocale = $localeFromCookie;
        } else {
            $userLocale = str_replace('-', '_', Yii::$app->language);   // sometimes yii can set the language as en-US for example
        }

        if (!Locale::isLocaleUsed($userLocale))
        {
            // Revert to default locale if the provided locale is not being used
            // This should perhaps throw an exception instead
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
