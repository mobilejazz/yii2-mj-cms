<?php
namespace mobilejazz\yii2\cms\common\assets;

use yii\web\AssetBundle;

class JquerySlimScrollAsset extends AssetBundle
{

    public $sourcePath = '@bower/jquery-slimscroll';

    public $js = [
        'jquery.slimscroll.min.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];

  
}
