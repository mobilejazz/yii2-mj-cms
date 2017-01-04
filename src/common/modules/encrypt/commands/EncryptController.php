<?php

namespace mobilejazz\yii2\cms\common\modules\encrypt\commands;

use mobilejazz\yii2\cms\common\Modules\encrypt\components\Encrypter;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Encrypt a string using your current encrypter configuration.
 *
 */
class EncryptController extends Controller
{

    /**
     * Encrypts a string using your current encrypter configuration.
     */
    public function actionIndex()
    {
        $encryptedString = $this->getEncrypter()
                                ->encrypt($this->prompt("\nType here the string to encrypt:"));

        $this->stdout("\nEncrypted String:\n");
        $this->stdout($encryptedString, Console::FG_GREEN);
        $this->stdout("\n\n");
    }


    /**
     * Returns the current instance of the encrypter component.
     *
     * @return Encrypter
     */
    private function getEncrypter()
    {
        try
        {
            return $this->module->encrypter;

        }
        catch (\Exception $exc)
        {
            $this->stdout("The encrypter configuration file \"encrypter.php\" was not found in your config directory.\n", Console::FG_RED);
        }

        return null;
    }

}