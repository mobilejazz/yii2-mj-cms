<?php

namespace mobilejazz\yii2\cms\backend;

use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\helpers\ArrayHelper;

class Module extends \yii\base\Module
{

    public $controllerNamespace = 'mobilejazz\yii2\cms\backend\controllers';

    public $configMerge = [];

    public $configOverride;

    public function init()
    {
        parent::init();

        Yii::configure($this, $this->getConfig());

    }

    /**
     * @return array
     */
    private function getConfig(){
        $config = $this->configOverride;

        if(!isset($config)){
            $default = require(__DIR__ . '/config.php');
            $config = ArrayHelper::merge($default, $this->configMerge);
        }

        return $config;
    }


}
