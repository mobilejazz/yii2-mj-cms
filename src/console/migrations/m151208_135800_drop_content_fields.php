<?php

use yii\db\Migration;

class m151208_135800_drop_content_fields extends Migration
{
    public function safeDown()
    {
        echo "cannot be reverted \n";
    }

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->dropForeignKey('fk_content_fields_content', '{{%content_fields}}');
        $this->dropTable('{{%content_fields}}');
    }
}
