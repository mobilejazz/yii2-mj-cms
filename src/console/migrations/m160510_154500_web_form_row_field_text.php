<?php

use yii\db\Migration;

class m160510_154500_web_form_row_field_text extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->alterColumn('web_form_row_field', 'hint', 'text');
    }


    public function down()
    {
        echo "Do nothing";
    }
}
