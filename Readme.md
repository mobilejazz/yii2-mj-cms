
![Mobile Jazz Yii2 CMS](https://raw.githubusercontent.com/mobilejazz/metadata/master/images/banners/mobile-jazz-yii2-cms-banner.png)

# Yii2 MJ CMS

A content management system for the Yii2 framework with database driven i18n support, webforms and rich component based views.

## Getting Started

The CMS is comprised of 2 modules, one for the backend administration and another for the frontend.

To enable the backend module in your backend project you can use the following configuration:

    ...,
    'modules'             => [
        'cmsbackend'              => [
            'class'         => 'mobilejazz\yii2\cms\backend\Module',
            'configMerge'   => [
                'components' => [
                    'sidebar' => [
                        'menuItems' => [
                            [
                                    'label' => 'Example',
                                    'url' => ['/example/index'],
                                    'icon' => '<i class="fa fa-beer"></i>'
                            ]
                        ]
                    ],
                    'urlManagerFrontend'    => [
                        'baseUrl'   => 'http://192.168.99.100'
                    ]
                ]
            ]
        ],
        'gridview'    => [
            'class' => '\kartik\grid\Module',
        ],
    ],
    'components'          => [
        'view'     => [
            'theme' => [
                'basePath'  => '@webroot',
                'baseUrl'   => '@web',
                'pathMap'   => [
                    '@app/views/layouts' => [                               # Use default layouts from backend
                        '/mobilejazz/yii2-mj-cms/src/backend/views',
                        '@mobilejazz/yii2/cms/backend/views'
                    ],
                    '/mobilejazz/yii2-mj-cms/src/backend/views' => [        # Allow for overriding all other backend views locally, local development
                        '@app/views'
                    ],
                    '@mobilejazz/yii2/cms/backend/views' => [               # Allow for overriding all other backend views locally, when used as a remote library
                        '@app/views'
                    ]
                ]
            ]
        ],
        'user'               => [
            'identityClass'   => 'mobilejazz\yii2\cms\common\models\User',                      # Shared with the frontend
            'enableAutoLogin' => true,
        ],
        'log'                => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => [ 'error', 'warning', 'info' ],
                ],
            ],
        ],        
        'urlManager'         => [   
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules'           => [
                '/'                     => 'cmsbackend/site/index',
                [ 'class' => 'mobilejazz\yii2\cms\backend\components\BackendUrlRules']          # Provides some convenience path mappings 
            ],
        ],
    ],
    'as locale'           => [
        'class'                   => 'mobilejazz\yii2\cms\common\behaviors\LocaleBehavior',     # For setting the language
        'enablePreferredLanguage' => false,
        'cookieName'              => '_backendLocale'
    ],
    ... 
    
To enable the frontend module in your frontend project you can use the following:

    ...,
    'modules'             => [
        'cmsfrontend'       => 'mobilejazz\yii2\cms\frontend\Module'
    ],
    'components'          => [
        'view' => [
            'theme' => [
                'pathMap' => [
                    '/mobilejazz/yii2-mj-cms/src/frontend/views' => [       # override views when developing locally
                        '@app/views'                        
                    ],
                    '@mobilejazz/yii2/cms/frontend/views' => [              # override views when using as a library
                        '@app/views'                 
                    ]
                ]
            ],
        ],
        'assetManager'       => [
            'appendTimestamp' => true,
            'linkAssets'      => true
        ],
        'user'               => [
            'identityClass'   => 'mobilejazz\yii2\cms\common\models\User',  # Shared with the frontend
            'enableAutoLogin' => true,
        ],
        'log'                => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => [ 'error', 'warning' ],
                ],
            ],
        ],
        'errorHandler'       => [
            'errorAction' => 'cmsfrontend/site/error',
        ],
        'urlManager'         => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules'           => [
                [
                    'class'             => 'mobilejazz\yii2\cms\frontend\components\FrontendUrlRules',  # Performs url mapping to CMS content
                ]
            ],
        ],
        'frontendUrlManager' => [
            'class' => 'mobilejazz\yii2\cms\frontend\components\FrontendUrlRules'                       # Re-declared as a utility for ensuring url's are constructed correctly
        ]
    ],
    'as locale'           => [
        'class'                   => 'mobilejazz\yii2\cms\common\behaviors\LocaleBehavior',             # Determines the selected locale
        'enablePreferredLanguage' => true,
        'cookieName'              => '_frontendLocale'
    ],
    ...

## Test Project

To see the CMS in action you can checkout the [test project](https://github.com/mobilejazz/yii2-mj-cms-test). 

## Project Maintainer

This open source project is maintained by [Brian McGee](https://github.com/brianmcgee) and [Pol Batlló](https://github.com/polbatllo).

## License

    Copyright 2016 MobileJazz

    Licensed under the Apache License, Version 2.0 (the "License");
    you may not use this file except in compliance with the License.
    You may obtain a copy of the License at

      http://www.apache.org/licenses/LICENSE-2.0

    Unless required by applicable law or agreed to in writing, software
    distributed under the License is distributed on an "AS IS" BASIS,
    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    See the License for the specific language governing permissions and
    limitations under the License.