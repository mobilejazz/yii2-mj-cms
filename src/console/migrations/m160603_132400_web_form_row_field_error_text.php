<?php

use yii\db\Migration;

class m160603_132400_web_form_row_field_error_text extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->addColumn('{{%web_form_row_field}}', 'error_message', 'string AFTER `hint`');
    }


    public function down()
    {
        echo "Do nothing";
    }
}
