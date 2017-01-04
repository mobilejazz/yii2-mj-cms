<?php

namespace mobilejazz\yii2\cms\common\assets;

use yii\web\AssetBundle;

class AceEditorAsset extends AssetBundle
{

    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/ace-builds/src-min-noconflict';

    /**
     * @inheritdoc
     */
    public $js = [
        'ace.js'
    ];
    
}