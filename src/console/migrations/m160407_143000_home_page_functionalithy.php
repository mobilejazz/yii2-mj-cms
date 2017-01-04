<?php

use yii\db\Migration;

class m160407_143000_home_page_functionalithy extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // WEB_FORM_SUBMISSION
        $this->addColumn('{{%content_source}}', 'is_homepage', 'int(11) NOT NULL DEFAULT 0 AFTER `status`');
    }


    public function down()
    {
        echo "Do nothing";
    }
}
