<?php

use yii\db\Migration;

class m160414_193000_user_profile_extensions extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // WEB_FORM_SUBMISSION
        $this->addColumn('{{%user_profile}}', 'addressline1', 'string(255) AFTER `about`');
        $this->addColumn('{{%user_profile}}', 'addressline2', 'string(255) AFTER `addressline1`');
        $this->addColumn('{{%user_profile}}', 'city', 'string(255) AFTER `addressline2`');
        $this->addColumn('{{%user_profile}}', 'postalcode', 'string(255) AFTER `city`');
        $this->addColumn('{{%user_profile}}', 'phonetype', 'string(255) AFTER `postalcode`');
        $this->addColumn('{{%user_profile}}', 'phone', 'string(255) AFTER `phonetype`');
    }


    public function down()
    {
        echo "Do nothing";
    }
}
