<?php
namespace mobilejazz\yii2\cms\common\assets;

use yii\web\AssetBundle;

class FastClickAsset extends AssetBundle
{

    public $sourcePath = '@bower/fastclick';

    public $js = [
        'lib/fastclick.js'
    ];

}
