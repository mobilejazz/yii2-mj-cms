<?php
namespace mobilejazz\yii2\cms\common\assets;

use yii\web\AssetBundle;

class JQueryCookieAsset extends AssetBundle
{

    public $sourcePath = '@bower/jquery.cookie';

    public $js = [
        'jquery.cookie.js'
    ];
    
    public $depends = [
        'yii\web\JqueryAsset'
    ];

}
