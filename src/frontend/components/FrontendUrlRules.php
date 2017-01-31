<?php
namespace mobilejazz\yii2\cms\frontend\components;

use mobilejazz\yii2\cms\common\models\ContentSlug;
use mobilejazz\yii2\cms\common\models\Locale;
use mobilejazz\yii2\cms\common\models\UrlRedirect;
use yii;
use yii\base\InvalidConfigException;
use yii\base\Object;
use yii\helpers\ArrayHelper;
use yii\web\Request;
use yii\web\UrlRuleInterface;

class FrontendUrlRules extends Object implements UrlRuleInterface
{

    public $baseUrl;

    public $translationCategory = 'url';

    public $staticRoutes = [];

    public $module = 'cmsfrontend';

    private $_hostInfo;

    private $initialised = false;

    private $_staticRoutes = [
        ''                       => 'cmsfrontend/site/index',
        '/'                      => 'cmsfrontend/site/index',
        'set-locale'             => 'cmsfrontend/site/set-locale',
        'about'                  => 'cmsfrontend/site/about',
        'search'                 => 'cmsfrontend/site/search',
        'contact-us'             => 'cmsfrontend/site/contact',
        'sitemap'                => 'cmsfrontend/site/sitemap',
        'login'                  => 'cmsfrontend/site/login',
        'logout'                 => 'cmsfrontend/site/logout',
        'profile'                => 'cmsfrontend/site/profile',
        'signup'                 => 'cmsfrontend/site/signup',
        'request-password-reset' => 'cmsfrontend/site/request-password-reset',
        'reset-password'         => 'cmsfrontend/site/reset-password',
        'send-mail'              => 'cmsfrontend/site/send-confirmation-mail',
        'submit'                 => 'cmsfrontend/webform/submission/submit'
    ];


    public function createBaseUrl($route, $params)
    {
        $url = $this->createUrl(null, $route, $params);

        if ($url == false)
        {
            $url = Yii::$app->urlManager->getBaseUrl();
        }

        if (strpos($url, '://') === false)
        {
            $url = $this->getHostInfo() . $url;
        }

        return $url;
    }


    /**
     * Creates a URL according to the given route and parameters.
     *
     * @param \yii\web\UrlManager $manager the URL manager
     * @param string              $route   the route. It should not have slashes at the beginning or the end.
     * @param array               $params  the parameters
     *
     * @return string|boolean the created URL, or false if this rule cannot be used for creating this URL.
     */
    public function createUrl($manager, $route, $params)
    {

        $this->init();

        //If a parameter is defined and not empty - add it to the URL
        $url = '';

        if (array_key_exists('lang', $params) || array_key_exists('slug', $params))
        {
            if (array_key_exists('lang', $params) && !empty($params[ 'lang' ]))
            {
                if (Locale::isMultiLanguageSite())
                {
                    $url .= '/' . $params[ 'lang' ];
                }
            }

            if (array_key_exists('slug', $params) && !empty($params[ 'slug' ]))
            {
                if (Locale::isMultiLanguageSite())
                {
                    $url .= '/' . $params[ 'slug' ];
                }
                else
                {
                    $url = '/' . $url . $params[ 'slug' ];
                }
            }
        }
        else
        {
            $url = $route;
        }

        unset($params['lang']);
        unset($params['slug']);

        if(count($params) > 0){
            $url = $url . '?' . http_build_query($params);
        }

        $baseUrl = $this->getBaseUrl();

        Yii::trace("Base url = $baseUrl", __METHOD__);

        if (isset($baseUrl))
        {
            $url = $baseUrl . $url;
        }

        Yii::trace("Created url = $url", __METHOD__);

        return $url;
    }


    public function init()
    {
        if ($this->initialised)
        {
            return;
        }

        // Replace cmsfrontend with whatever custom module name has been configured
        foreach ($this->_staticRoutes as $key => $value)
        {
            $this->_staticRoutes[ $key ] = str_replace('cmsfrontend', $this->module, $value);
        }

        $this->_staticRoutes = ArrayHelper::merge($this->_staticRoutes, $this->staticRoutes);

        $this->initialised = true;

        parent::init();
    }


    private function getBaseUrl()
    {
        return isset($this->baseUrl) ? $this->baseUrl : Yii::$app->urlManager->baseUrl;
    }


    /**
     * Returns the host info that is used by [[createBaseUrl()]] to prepend to created URLs.
     * @return string the host info (e.g. "http://www.example.com") that is used by [[createBaseUrl()]] to prepend to created URLs.
     * @throws InvalidConfigException if running in console application and [[hostInfo]] is not configured.
     */
    public function getHostInfo()
    {
        if ($this->_hostInfo === null)
        {
            $request = Yii::$app->getRequest();
            if ($request instanceof Request)
            {
                $this->_hostInfo = $request->getHostInfo();
            }
            else
            {
                throw new InvalidConfigException('Please configure FrontendUrlRules::hostInfo.');
            }
        }

        return $this->_hostInfo;
    }


    /**
     * Parses the given request and returns the corresponding route and parameters.
     *
     * @param \yii\web\UrlManager $manager the URL manager
     * @param Request             $request the request component
     *
     * @return array|bool the parsing result. The route and the parameters are returned as an array.
     * If false, it means this rule cannot be used to parse this path info.
     */
    public function parseRequest($manager, $request)
    {
        $this->init();

        // Get the path info.

        $pathInfo = $request->getPathInfo();

        // Remove base url if path info is fully qualified

        $baseUrl = $this->getBaseUrl();

        if ($pathInfo === '')
        {
            $pathInfo = '/';
        }

        Yii::info("Parsing request: pathInfo = '$pathInfo', baseUrl = '$baseUrl'", __METHOD__);

        if (isset($baseUrl) && strlen($baseUrl) > 0)
        {
            $pathInfo = str_replace($baseUrl, '', $pathInfo); // remove the base url if present
            Yii::info("Removed baseUrl from pathInfo: pathInfo = $pathInfo", __METHOD__);
        }

        // Actions that need to escape the content management url system.

        foreach ($this->_staticRoutes as $path => $route)
        {

            $translatedPath = Yii::t($this->translationCategory, $path);
            if ($translatedPath !== $pathInfo)
            {
                continue;
            }

            Yii::info("Static route found: path = $path, translatedPath = $translatedPath, route = $route", __METHOD__);

            return [ $route, [] ];
        }

        // Check if any redirects have been setup

        $url_redirects = UrlRedirect::find()
                                    ->orderBy([ 'updated_at' => SORT_DESC ])
                                    ->all();

        foreach ($url_redirects as $redirect)
        {
            $origin_slug = $redirect->origin_slug;
            if (strcmp("/" . $pathInfo, $origin_slug) == 0)
            {

                $destination_slug = $redirect->destination_slug;

                Yii::trace("Url redirect found: origin_slug = $origin_slug, destination_slug = $destination_slug", __METHOD__);

                Yii::$app->getResponse()
                         ->redirect($destination_slug, 301)
                         ->send();
            }
        }

        // If no redirect has been found, check on the content redirects.

        $route = $this->module . '/site/content';

        //parameters in the URL (language, category, slug).
        $params = [];

        //  $parameters = explode('/', $pathInfo);

        // Check  if the app has more than one language active
        if (Locale::isMultiLanguageSite())
        {

            preg_match('/^\/?(\w{2}_\w{2})\/?(.*)$/', $pathInfo, $matches);

            //Remove the lang out of the pathinfo
            $url_locale        = $matches[ 1 ];
            $stripped_pathInfo = $matches[ 2 ];

            Yii::trace("Multi lang detected: stripped_pathinfo = $stripped_pathInfo", __METHOD__);

            /**
             * Search for the pathinfo
             * @var $slug ContentSlug
             */
            $slug = ContentSlug::find()
                               ->where([ 'slug' => $stripped_pathInfo, 'language' => $url_locale ])
                               ->one();

        } // Else look directly for the slug.
        else
        {

            $slug = ContentSlug::find()
                               ->where([ 'slug' => $pathInfo, 'language' => Yii::$app->language ])
                               ->one();
        }

        // Actions to take if we have found a Slug.
        if (isset($slug))
        {

            $id = $slug->id;
            Yii::trace("Slug found: id = $id");

            // First check that the language defined is the same as the slugs language.
            if (strcmp(Yii::$app->language, $slug->language) != 0)
            {

                $current_slug = $slug->content->getCurrentSlug(Yii::$app->language);

                $link = '/' . $current_slug->language . '/' . $current_slug->slug;

                Yii::trace("Incorrect locale, redirect = $link", __METHOD__);

                Yii::$app->getResponse()
                         ->redirect($link, 301)
                         ->send();
            }

            if ($slug->isActive())
            {
                // Else save the params.
                $params[ 'lang' ] = $slug->language;
                $params[ 'slug' ] = $slug->slug;

                Yii::trace("Active slug found, route = $route", __METHOD__);

                return [ $route, $params ];

            }  // If the slug is different that the one received, redirect.
            else
            {

                $current_slug = $slug->content->getCurrentSlug($slug->language);

                $link = '/' . $current_slug->language . '/' . $current_slug->slug;

                Yii::trace("Redirect 2 = $link", __METHOD__);

                Yii::$app->getResponse()
                         ->redirect($link, 301)
                         ->send();
            }
        } // If we have not found a slug until now, throw a new NotFoundHttpException.
        else
        {

//            throw new NotFoundHttpException(Yii::t("app", "It looks like the content you are trying to access can not be found.
//                            Route: {route}.
//                            Params: {params}", [
//                'route' => json_encode($route),
//                'params' => json_encode($params),
//            ]));
        }

        return false;
    }

}