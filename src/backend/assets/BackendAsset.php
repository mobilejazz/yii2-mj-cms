<?php

namespace mobilejazz\yii2\cms\backend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since  2.0
 */
class BackendAsset extends AssetBundle
{

    public $sourcePath = '@mobilejazz/yii2/cms/backend/web';

    public $css = [
        'css/custom.css',
        'js/skin/bootstrapck/editor.css'
    ];

    public $js = [
        'js/custom.js',
        'js/theme-config.js',
        'js/content-loader.js',
        'js/skin/bootstrapck/skin.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'mobilejazz\yii2\cms\common\assets\AdminLte',
        'mobilejazz\yii2\cms\common\assets\Html5ShivAsset',
        'lavrentiev\widgets\toastr\assets\ToastrAsset',
        'mobilejazz\yii2\cms\common\assets\CKEditorAsset',
        'mobilejazz\yii2\cms\common\assets\AceEditorAsset',
    ];
}
