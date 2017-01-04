<?php

use yii\db\Migration;

class m160309_133500_sensitive_fields_web_forms extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // WEB_FORM_SUBMISSION
        $this->addColumn('{{web_form_row_field}}', 'is_sensitive', 'int(11) NOT NULL DEFAULT 0 AFTER `required`');
    }


    public function down()
    {
        echo "Do nothing";
    }
}
