<?php

namespace mobilejazz\yii2\cms\frontend;

use Yii;
use yii\helpers\ArrayHelper;

class Module extends \yii\base\Module
{

    public $controllerNamespace = 'mobilejazz\yii2\cms\frontend\controllers';

    public $configMerge = [];

    public $configOverride;

    public function init()
    {
        parent::init();

        $config = $this->configOverride;

        if(!isset($config)){
            $default = require(__DIR__ . '/config.php');
            $config = ArrayHelper::merge($default, $this->configMerge);
        }

        Yii::configure($this, $config);

    }


    
}
