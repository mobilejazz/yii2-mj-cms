<?php

namespace mobilejazz\yii2\cms\common\assets;

use yii\web\AssetBundle;

class AdminLte extends AssetBundle
{

    public $sourcePath = '@bower/admin-lte/dist';

    public $js = [
        'js/app.min.js'
    ];

    public $css = [
        'css/AdminLTE.min.css',
        'css/skins/_all-skins.min.css'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'mobilejazz\yii2\cms\common\assets\FontAwesome',
        'mobilejazz\yii2\cms\common\assets\JquerySlimScrollAsset',
        'mobilejazz\yii2\cms\common\assets\AdminLteConfig',
        'mobilejazz\yii2\cms\common\assets\SweetAlertAsset',
        'mobilejazz\yii2\cms\common\assets\FlagIconAsset'
    ];
    
}
