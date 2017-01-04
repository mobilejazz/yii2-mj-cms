<?php

namespace mobilejazz\yii2\cms\backend\modules\filemanager\assets;

use yii\web\AssetBundle;

class BowerDependencies extends AssetBundle
{

    public $sourcePath = '@bower';

    public $css = [
    ];

    public $js = [
        'clipboard/dist/clipboard.min.js'
    ];
}