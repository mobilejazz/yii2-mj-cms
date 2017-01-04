<?php

namespace mobilejazz\yii2\cms\backend\modules\filemanager\assets;

use yii\web\AssetBundle;

class FileInputAsset extends AssetBundle
{

    public $sourcePath = '@mobilejazz/yii2/cms/backend/web';

    public $js = [
        'js/file-manager/fileinput.js',
    ];

    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset',
        'mobilejazz\yii2\cms\backend\modules\filemanager\assets\ModalAsset',
    ];
}
