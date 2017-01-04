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
        if (count($params) < 1)
        {
            return false;
        }

        $path           = $params[ 0 ];
        $pathComponents = explode('/', $path);

        if ($pathComponents[ 0 ] != $this->modulePrefix)
        {
            return false;
        }

        $params[ 0 ] = implode('/', array_slice($pathComponents, 1));         // drop the module prefix
        $result      = $manager->createUrl($params);                             // create the url
        $params[ 0 ] = $path;                                                 // return the path to original value

        return $result;
    }

}