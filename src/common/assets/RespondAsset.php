<?php
namespace mobilejazz\yii2\cms\common\assets;

use yii\web\AssetBundle;

class RespondAsset extends AssetBundle
{

    public $sourcePath = '@bower/respond';

    public $js = [
        'dest/respond.min.js'
    ];

}
