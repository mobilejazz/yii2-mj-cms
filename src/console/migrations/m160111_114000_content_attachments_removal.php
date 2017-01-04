<?php

use yii\db\Migration;

class m160111_114000_content_attachments_removal extends Migration
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

        $this->dropTable('{{content_attachment}}');
    }
}
