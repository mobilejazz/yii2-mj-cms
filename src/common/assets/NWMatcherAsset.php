<?php
namespace mobilejazz\yii2\cms\common\assets;

use yii\web\AssetBundle;

class NWMatcherAsset extends AssetBundle
{

    public $sourcePath = '@bower/nwmatcher';

    public $js = [
        'src/nwmatcher.js'
    ];

    public $jsOptions = [
        'position'  => \yii\web\View::POS_HEAD,
        'condition' => 'lt IE 9'
    ];


}
