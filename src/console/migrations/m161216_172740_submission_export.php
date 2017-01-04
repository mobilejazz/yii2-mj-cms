<?php

use yii\db\Migration;

class m161216_172740_submission_export extends Migration
{

    public function up()
    {
        $this->addColumn("web_form_submission", "exported", "INT DEFAULT 0 AFTER submission");
    }


    public function down()
    {
        echo "m161216_172740_submission_export cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
