<?php

namespace mobilejazz\yii2\cms\backend\modules\filemanager\assets;

use yii\web\AssetBundle;

class FilemanagerAsset extends AssetBundle
{

    public $sourcePath = '@mobilejazz/yii2/cms/backend/web';

    public $css = [
        'css/file-manager/filemanager.css',
    ];

    public $js = [
        'js/file-manager/filemanager.js',
    ];

    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset',
        'mobilejazz\yii2\cms\common\assets\AdminLte',
        'mobilejazz\yii2\cms\backend\modules\filemanager\assets\BowerDependencies',
    ];
}
