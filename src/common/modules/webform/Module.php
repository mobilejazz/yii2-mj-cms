<?php

namespace mobilejazz\yii2\cms\common\modules\webform;

use yii;
use yii\base\Module as BaseModule;

class Module extends BaseModule
{

    public $controllerNamespace = 'mobilejazz\yii2\cms\common\modules\webform\controllers';

    /**
     * @var array
     */
    public $config;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

}