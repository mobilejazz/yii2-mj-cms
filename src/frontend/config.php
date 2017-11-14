<?php


return [

    'layout' => 'main',

    'modules' => [
        'cms-common' => 'mobilejazz\yii2\cms\common\Module',
        'webform' => 'mobilejazz\yii2\cms\common\modules\webform\Module'
    ],
    'components' => [
        'previewService' => [
            'class' => 'mobilejazz\yii2\cms\common\components\PreviewService',
            'salt' => 'define random salt!'
        ],
    ]
];