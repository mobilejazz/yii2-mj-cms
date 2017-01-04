<?php

namespace mobilejazz\yii2\cms\common\modules\encrypt\commands;

use mobilejazz\yii2\cms\common\Modules\encrypt\components\Encrypter;
use yii\console\Controller;
use yii\helpers\Console;

class DecryptController extends Controller
{

    /**
     * Decrypts a string using your current encrypter configuration.
     */
    public function actionIndex()
    {
        $decryptedString = $this->getEncrypter()
                                ->decrypt($this->prompt("\nType here the string to decrypt:"));

        $this->stdout("\nDecrypted String:\n");
        $this->stdout($decryptedString, Console::FG_GREEN);
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