<?php

use yii\db\Migration;

class m151118_170700_content_fields_system extends Migration
{

    public function down()
    {
        echo "Nothing to do.";
    }

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->addColumn('{{content_fields}}', 'system', 'integer AFTER `repeatable`');
    }
}
