<?php

use yii\db\Migration;

class m151119_132600_locales_used extends Migration
{

    public function down()
    {
        echo "Nothing to do";
    }

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->addColumn('{{locale}}', 'used', 'integer default 1 AFTER `default`');
    }
}
