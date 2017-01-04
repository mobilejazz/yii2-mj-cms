<?php

use yii\db\Migration;

class m151203_191000_right_to_left_locale extends Migration
{
    public function down()
    {
    }

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->addColumn('{{locale}}', 'rtl', 'integer default 0 AFTER `used`');
    }
}