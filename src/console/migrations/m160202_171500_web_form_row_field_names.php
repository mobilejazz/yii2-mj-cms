<?php

use yii\db\Migration;

class m160202_171500_web_form_row_field_names extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // WEB_FORM_SUBMISSION
        $this->addColumn('{{web_form_row_field}}', 'name', 'string(255) AFTER `required`');
    }

    public function down()
    {
        echo "Do nothing";
    }
}
