<?php

namespace mobilejazz\yii2\cms\common\assets;

use yii\web\AssetBundle;

class Flot extends AssetBundle
{

    public $sourcePath = '@bower/flot';

    public $js = [
        'jquery.flot.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
    
}
