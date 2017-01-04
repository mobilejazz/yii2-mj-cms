<?php
/**
 * Contains the module class used to have encrypt console commands.
 *
 */

namespace mobilejazz\yii2\cms\common\modules\encrypt;

use mobilejazz\yii2\cms\common\Modules\encrypt\components\Encrypter;
use yii\base\BootstrapInterface;
use yii\base\Module as BaseModule;
use yii\console\Application;

/**
 * Bootstrap the module to allow the use of the console commands.
 *
 * @property-read Encrypter $encrypter
 */
class Module extends BaseModule implements BootstrapInterface
{
    
    public $config;

    public function init()
    {

        $this->setComponents([
            'encrypter' => $this->config,
        ]);

        parent::init();
    }


    public function bootstrap($app)
    {
        if ($app instanceof Application)
        {

            $this->controllerNamespace = 'mobilejazz\yii2\cms\common\modules\encrypt\commands';
            $this->setAliases([
                '@encrypt/encrypter' => dirname(__FILE__),
            ]);
        }
    }
}