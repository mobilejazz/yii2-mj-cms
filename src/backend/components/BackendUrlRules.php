<?php

namespace mobilejazz\yii2\cms\backend\components;

use Yii;
use yii\base\Object;
use yii\helpers\ArrayHelper;
use yii\web\Request;
use yii\web\UrlRuleInterface;

class BackendUrlRules extends Object implements UrlRuleInterface
{

    public $controllers = [ ];

    public $modulePrefix = 'cmsbackend';

    private $_controllers = [
        'user',
        'i18n',
        'system-information',
        'content-source',
        'filemanager',
        'blog',
        'menu',
        'setting',
        'site',
        'system',
        'url-redirect',
        'web-form',
        'web-form-submission',
        'widget-menu',
        'locale'
    ];


    public function parseRequest($manager, $request)
    {
        $controllers = ArrayHelper::merge($this->_controllers, $this->controllers);  // config merge

        $pathInfo       = $request->getPathInfo();
        $pathComponents = explode('/', $pathInfo);

        $controllersJson = json_encode($controllers);

        Yii::trace("Parsing request for pathInfo = '$pathInfo', module = ${pathComponents[0]},   controllers = $controllersJson", __METHOD__);

        if ((count($pathComponents) < 1) || !in_array($pathComponents[ 0 ], $controllers))
        {
            return false;
        }

        $pathOverride = $this->modulePrefix . '/' . $pathInfo;

        Yii::trace("Overriding path with '$pathOverride'", __METHOD__);

        $request->setPathInfo($pathOverride);       // prepend module prefix
        $result = $manager->parseRequest($request);                         // parse the request
        $request->setPathInfo($pathInfo);                                   // return the pathInfo to original value

        return $result;
    }


    public function createUrl($manager, $route, $params)
    {
        $routeComponents = explode('/', $route);
        $controller = $routeComponents[0];

        if(!in_array($controller, $this->controllers)) return false;

        // add module prefix
        return $manager->createUrl(ArrayHelper::merge([$this->modulePrefix . '/' . $route], $params));
    }

}