<?php

use yii\db\Migration;

class m160811_164500_web_form_row_field_name_length extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->alterColumn('{{%web_form_row_field}}', 'name', 'text');
    }


    public function down()
    {
        echo "Do nothing";
    }
}
