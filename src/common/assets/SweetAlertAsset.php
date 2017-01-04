<?php

namespace mobilejazz\yii2\cms\common\assets;

use yii\web\AssetBundle;

class SweetAlertAsset extends AssetBundle
{

    public $sourcePath = '@bower/sweetalert/dist';

    public $js = [
        'sweetalert.min.js',
    ];

    public $css = [
        'sweetalert.css'
    ];

    /* Uncomment when working on this assets
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];*/
}