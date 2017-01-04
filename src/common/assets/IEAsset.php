<?php

namespace mobilejazz\yii2\cms\common\assets;

use yii\web\AssetBundle;

class IEAsset extends AssetBundle
{

    public $depends = [
        'mobilejazz\yii2\cms\common\assets\Html5ShivAsset',
        'mobilejazz\yii2\cms\common\assets\JquerySlimScrollAsset',
        'mobilejazz\yii2\cms\common\assets\NWMatcherAsset',
        'mobilejazz\yii2\cms\common\assets\SelectivizrAsset',
        'mobilejazz\yii2\cms\common\assets\RespondAsset',
    ];
    
}