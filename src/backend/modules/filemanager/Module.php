<?php

namespace mobilejazz\yii2\cms\backend\modules\filemanager;

use Yii;
use yii\base\Module as BaseModule;

class Module extends BaseModule
{

    /**
     * @var array default thumbnail size, using in filemanager view.
     */
    private static $defaultThumbSize = [ 128, 128 ];

    public $controllerNamespace = 'mobilejazz\yii2\cms\backend\modules\filemanager\controllers';

    /**
     *  Set true if you want to rename files if the name is already in use
     * @var boolean
     */
    public $rename = true;

    /**
     *  Set true to enable autoupload
     * @var boolean
     */
    public $autoUpload = false;

    /**
     * @var array upload routes
     */
    public $routes = [
        // base absolute path to web directory
        'baseUrl'    => '',
        // base web directory url
        'basePath'   => '@webroot',
        // path for uploaded files in web directory
        'uploadPath' => 'files',
    ];

    /**
     * @var array thumbnails info
     */
    public $thumbs = [
        'small'  => [
            'name' => 'Small size',
            'size' => [ 120, 80 ],
        ],
        'medium' => [
            'name' => 'Medium size',
            'size' => [ 400, 300 ],
        ],
        'large'  => [
            'name' => 'Large size',
            'size' => [ 800, 600 ],
        ],
    ];


    /**
     * @return array default thumbnail size. Using in filemanager view.
     */
    public static function getDefaultThumbSize()
    {
        return self::$defaultThumbSize;
    }


    public function init()
    {
        parent::init();
    }
}
