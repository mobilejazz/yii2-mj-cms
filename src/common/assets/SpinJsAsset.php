<?php
namespace mobilejazz\yii2\cms\common\assets;

use yii\web\AssetBundle;

class SpinJsAsset extends AssetBundle
{

    public $sourcePath = '@bower/spin.js';

    public $js = [
        'spin.min.js'
    ];

}
