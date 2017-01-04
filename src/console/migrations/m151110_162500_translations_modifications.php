<?php

use yii\db\Migration;

class m151110_162500_translations_modifications extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        // Add birth date to user table.
        $this->addColumn('{{content_fields}}', 'required', 'integer AFTER `type`');
        $this->addColumn('{{content_fields}}', 'repeatable', 'integer AFTER `required`');
    }


    public function down()
    {
        echo "Nothing to do";
    }
}
