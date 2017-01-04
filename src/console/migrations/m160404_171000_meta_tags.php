<?php

use yii\db\Migration;
use yii\db\Schema;

class m160404_171000_meta_tags extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // ADD META TAGS.
        $this->createTable('{{%content_meta_tag}}', [
            'id'         => Schema::TYPE_INTEGER . '(11) NOT NULL AUTO_INCREMENT',
            'content_id' => $this->integer()->notNull(),
            'language'   => $this->string(16)->notNull(),
            'name'       => $this->string(512)->notNull(),
            'content'    => $this->text()->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY (`id`, `content_id`, `language`)',
        ]);

        $this->addForeignKey('fk_content_meta_tag_content_source', '{{%content_meta_tag}}', 'content_id', '{{%content_source}}', 'id', 'cascade',
            'cascade');
    }


    public function down()
    {
        echo "Do nothing";
    }
}
