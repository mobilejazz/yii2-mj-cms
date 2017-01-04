<?php

namespace mobilejazz\yii2\cms\common\assets;

use yii\web\AssetBundle;

class Html5ShivAsset extends AssetBundle
{

    public $sourcePath = '@bower/html5shiv';

    public $js = [
        'dist/html5shiv.min.js'
    ];

    public $jsOptions = [
        'position'  => \yii\web\View::POS_HEAD,
        'condition' => 'lt IE 9'
    ];
    
}
