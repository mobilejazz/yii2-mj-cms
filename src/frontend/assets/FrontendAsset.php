<?php
namespace mobilejazz\yii2\cms\frontend\assets;


use yii\web\AssetBundle;

class FrontendAsset extends AssetBundle
{
    
    public $sourcePath = '@mobilejazz/yii2/cms/frontend/web';

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'mobilejazz\yii2\cms\common\assets\IEAsset'
    ];

    public $css = [
        'css/style.css'
    ];
}