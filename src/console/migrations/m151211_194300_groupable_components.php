<?php

use yii\db\Migration;

class m151211_194300_groupable_components extends Migration
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

        $this->addColumn('{{content_component}}', 'group_id', 'integer default 0 AFTER `repeatable`');
    }
}
