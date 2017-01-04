<?php
namespace mobilejazz\yii2\cms\common\assets;


use yii\web\AssetBundle;

class VenoboxAsset extends AssetBundle
{
    
    public $sourcePath = '@mobilejazz/yii2/cms/frontend/web/library/venobox';

    public $depends = [
        'yii\web\JqueryAsset'
    ];

    public $js = [
        'venobox/venobox.min.js'
    ];

    public $css = [
        'venobox/venobox.css'
    ];

}