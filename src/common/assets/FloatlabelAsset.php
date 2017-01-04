<?php

namespace mobilejazz\yii2\cms\common\assets;


use yii\web\AssetBundle;

class FloatlabelAsset extends AssetBundle
{
    
    public $sourcePath = '@mobilejazz/yii2/cms/frontend/web/library/floatlabel.js';

    public $js = [
        'floatlabels.min.js'
    ];
}