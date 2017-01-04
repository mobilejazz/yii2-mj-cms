<?php

use yii\db\Migration;

class m151208_132600_content extends Migration
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

        // CONTENT COMPONENTS
        $this->createTable('{{%content_component}}',
            [
                'id'         => $this->primaryKey(),
                'content_id' => $this->integer()->notNull(),
                'type'       => $this->integer()->notNull(),
                'language'   => $this->string(16)->notNull(),
                'title'      => $this->string()->notNull(),
                'order'      => $this->integer()->notNull(),
                'repeatable' => $this->integer()->notNull(),
                'is_child'   => $this->integer()->notNull(),
                'created_at' => $this->integer(),
                'updated_at' => $this->integer(),
            ],
            $tableOptions);

        // CONTENT (Translated)
        $this->createTable('{{%component_field}}',
            [
                'id'           => $this->primaryKey(),
                'component_id' => $this->integer()->notNull(),
                'type'         => $this->integer()->notNull(),
                'language'     => $this->string(16)->notNull(),
                'order'        => $this->integer()->notNull(),
                'required'     => $this->integer()->notNull(),
                'repeatable'   => $this->integer()->notNull(),
                'is_child'     => $this->integer()->notNull(),
                'text'         => $this->text(),
                'created_at'   => $this->integer(),
                'updated_at'   => $this->integer(),
            ],
            $tableOptions);

        $this->addForeignKey('fk_component_field_content_component', '{{%component_field}}', 'component_id', '{{%content_component}}', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_content_component_content', '{{%content_component}}', 'content_id', '{{%content_source}}', 'id', 'cascade', 'cascade');
    }
}