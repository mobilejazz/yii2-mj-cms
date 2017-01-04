<?php

namespace mobilejazz\yii2\cms\backend\modules\filemanager\assets;

use yii\web\AssetBundle;

class ModalAsset extends AssetBundle
{

    public $sourcePath = '@mobilejazz/yii2/cms/backend/web';

    public $js = [
    ];

    public $css = [
        'css/file-manager/modal.css',
    ];

    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}
