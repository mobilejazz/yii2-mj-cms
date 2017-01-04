<?php
namespace mobilejazz\yii2\cms\common\assets;

use yii\web\AssetBundle;

class ModernizrAsset extends AssetBundle
{

    public $sourcePath = '@bower/modernizr';

    public $js = [
        'modernizr.js'
    ];

}
