<?php

use mobilejazz\yii2\cms\common\models\WebFormSubmission;
use yii\db\Migration;

class m160714_103200_encrypt_data_of_web_form_submissions extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {

        /** @var WebFormSubmission $submission */
        foreach (WebFormSubmission::find()
                                  ->all() as $submission)
        {
            $submission->submission = \Yii::$app->encrypter->encrypt($submission->submission);
            $submission->save();
        }

    }


    public function safeDown()
    {
        echo 'Do nothing';
    }

}