<?php

return [

    'layout' => 'main',

    'modules'    => [
        'cms-common'  => [
            'class' => 'mobilejazz\yii2\cms\common\Module'
        ],
        'i18n'        => [
            'class'        => 'mobilejazz\yii2\cms\backend\modules\i18n\Module',
            'defaultRoute' => 'i18n-message/index',
        ],
        'filemanager' => 'mobilejazz\yii2\cms\backend\modules\filemanager\Module'
    ],
    'components' => [
        'sidebar'            => [
            'class'     => 'mobilejazz\yii2\cms\common\components\SidebarConfig',
            'menuItems' => [
                [
                    'label' => 'Home',
                    'url'   => [ '/site/index' ],
                    'icon'  => '<i class="fa fa-home"></i>',
                ],
                [
                    'label'   => 'Content',
                    'url'     => [ '/content-source/index' ],
                    'icon'    => '<i class="fa fa-file-o"></i>',
                    'visible' => 'mobilejazz\yii2\cms\backend\controllers\ContentSourceController'
                ],
                [
                    'label'   => 'File Manager',
                    'url'     => '#',
                    'icon'    => '<i class="fa fa-file"></i>',
                    'visible' => 'mobilejazz\yii2\cms\backend\modules\filemanager\controllers\DefaultController',
                    'items'   => [
                        [
                            'label' => 'Media',
                            'url'   => [ '/filemanager/default/index' ],
                            'icon'  => '<i class="fa fa-image"></i>'
                        ],
                        [
                            'label' => 'Settings',
                            'url'   => [ '/filemanager/default/settings' ],
                            'icon'  => '<i class="fa fa-wrench"></i>'
                        ]
                    ],
                ],
                [
                    'label'   => 'Web Forms',
                    'url'     => [ '/web-form/index' ],
                    'icon'    => '<i class="fa fa-code"></i>',
                    'visible' => 'mobilejazz\yii2\cms\backend\controllers\WebFormController'
                ],
                [
                    'label'   => 'Menus',
                    'url'     => [ '/menu/index' ],
                    'icon'    => '<i class="fa fa-list"></i>',
                    'visible' => 'mobilejazz\yii2\cms\backend\controllers\MenuController'
                ],
                [
                    'label'   => 'Users',
                    'url'     => [ '/user/index' ],
                    'icon'    => '<i class="fa fa-user"></i>',
                    'visible' => 'mobilejazz\yii2\cms\backend\controllers\UserController'
                ],
                [
                    'label'   => 'Languages',
                    'url'     => [ '/locale/index' ],
                    'icon'    => '<i class="fa fa-language" ></i > ',
                    'visible' => 'mobilejazz\yii2\cms\backend\controllers\LocaleController'
                ],
                [
                    'label'   => 'Translations',
                    'url'     => [ '/i18n/i18n-message/index' ],
                    'icon'    => '<i class="fa fa-flag"></i>',
                    'visible' => 'mobilejazz\yii2\cms\backend\modules\i18n\controllers\I18nMessageController'
                ],
                [
                    'label'   => 'Url redirects',
                    'url'     => [ '/url-redirect/index' ],
                    'icon'    => '<i class="fa fa-link"></i>',
                    'visible' => 'mobilejazz\yii2\cms\backend\controllers\UrlRedirectController'
                ],
                [
                    'label'   => 'System Information',
                    'url'     => [ '/system-information/index' ],
                    'icon'    => '<i class="fa fa-info-circle"></i>',
                    'visible' => true
                ],
                [
                    'label'   => 'Settings',
                    'url'     => [ '/setting/index' ],
                    'icon'    => '<i class="fa fa-cogs"></i>',
                    'visible' => true
                ]
            ]
        ],
        'urlManagerFrontend' => [
            'class' => 'mobilejazz\yii2\cms\frontend\components\FrontendUrlRules'
        ],
        'previewService' => [
            'class' => 'mobilejazz\yii2\cms\common\components\PreviewService',
            'salt' => 'define random salt!'
        ],
    ]
];