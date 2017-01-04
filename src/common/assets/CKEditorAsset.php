<?php

namespace mobilejazz\yii2\cms\common\assets;

use yii\web\AssetBundle;

class CKEditorAsset extends AssetBundle
{

    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/ckeditor';

    /**
     * @inheritdoc
     */
    public $js = [
        'ckeditor.js'
    ];
    
}