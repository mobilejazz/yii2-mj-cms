<?php

use yii\db\Migration;
use yii\db\Schema;

class m151029_120000_content extends Migration
{
    public function safeDown()
    {
        echo "m151029_120000_content cannot be reverted \n";
    }

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // CONTENT_SOURCE
        $this->createTable('{{%content_source}}',
            [
                'id'           => $this->primaryKey(),
                'view'         => $this->integer()->notNull(),
                'author_id'    => $this->integer(),
                'updater_id'   => $this->integer(),
                'status'       => $this->smallInteger()->notNull()->defaultValue(0),
                'published_at' => $this->integer(),
                'created_at'   => $this->integer(),
                'updated_at'   => $this->integer(),
            ],
            $tableOptions);

        // CONTENT (Translated)
        $this->createTable('{{%content_fields}}',
            [
                'id'         => Schema::TYPE_INTEGER . '(11) NOT NULL AUTO_INCREMENT',
                'content_id' => $this->integer()->notNull(),
                'language'   => $this->string(16)->notNull(),
                'order'      => $this->integer()->notNull(),
                'type'       => $this->integer()->notNull(),
                'text'       => $this->text(),
                'created_at' => $this->integer(),
                'updated_at' => $this->integer(),
                'PRIMARY KEY (`id`, `language`, `order`)',
            ],
            $tableOptions);

        // CONTENT ATTACHMENT.
        $this->createTable('{{%content_attachment}}',
            [
                'id'         => Schema::TYPE_INTEGER . '(11) NOT NULL AUTO_INCREMENT',
                'content_id' => $this->integer()->notNull(),
                'language'   => $this->string(16)->notNull(),
                'path'       => $this->string()->notNull(),
                'base_url'   => $this->string(),
                'type'       => $this->string(),
                'size'       => $this->integer(),
                'name'       => $this->string(),
                'created_at' => $this->integer(),
                'PRIMARY KEY (`id`, `language`)',
            ]);

        // CONTENT SLUG.
        $this->createTable('{{%content_slug}}',
            [
                'id'         => Schema::TYPE_INTEGER . '(11) NOT NULL AUTO_INCREMENT',
                'content_id' => $this->integer()->notNull(),
                'language'   => $this->string(16)->notNull(),
                'slug'       => $this->string(1024)->notNull(),
                'title'      => $this->string(512),
                'system'     => $this->boolean(),
                'created_at' => $this->integer(),
                'updated_at' => $this->integer(),
                'PRIMARY KEY (`id`, `language`)',
            ]);

        $this->addForeignKey('fk_content_fields_content', '{{%content_fields}}', 'content_id', '{{%content_source}}', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_content_attachment_content', '{{%content_attachment}}', 'content_id', '{{%content_source}}', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_content_slug_content', '{{%content_slug}}', 'content_id', '{{%content_source}}', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_content_author', '{{%content_source}}', 'author_id', '{{%user}}', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_content_updater', '{{%content_source}}', 'updater_id', '{{%user}}', 'id', 'set null', 'cascade');
    }
}
