<?php
namespace mobilejazz\yii2\cms\common\assets;

use yii\web\AssetBundle;

class RemUnitPolyfillAsset extends AssetBundle
{

    public $sourcePath = '@bower/rem-unit-polyfill';

    public $js = [
        'js/rem.min.js'
    ];

}
