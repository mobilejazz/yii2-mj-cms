<?php
namespace mobilejazz\yii2\cms\common\assets;

use yii\web\AssetBundle;

class SelectivizrAsset extends AssetBundle
{

    public $sourcePath = '@bower/selectivizr';

    public $js = [
        'selectivizr.js'
    ];

    public $jsOptions = [
        'position'  => \yii\web\View::POS_HEAD,
        'condition' => 'lt IE 9'
    ];


}
