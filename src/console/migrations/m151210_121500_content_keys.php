<?php

use yii\db\Migration;

class m151210_121500_content_keys extends Migration
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
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        // Add google id to user profile table.
        $this->alterColumn('{{content_source}}', 'view', 'varchar(255)');
        $this->alterColumn('{{content_component}}', 'type', 'varchar(255)');
        $this->alterColumn('{{component_field}}', 'type', 'varchar(255)');
    }
}
