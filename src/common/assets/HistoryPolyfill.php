<?php

namespace mobilejazz\yii2\cms\common\assets;

use yii\web\AssetBundle;
use yii\web\View;

class HistoryPolyfill extends AssetBundle
{
    public $sourcePath = '@bower/html5-history-api';
    public $js = [
        'history.js'
    ];

    public $jsOptions = [
        'condition'=>'lte IE 9',
        'position' => View::POS_HEAD
    ];
}
