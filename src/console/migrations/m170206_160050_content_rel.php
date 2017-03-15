<?php

use yii\db\Migration;
use yii\db\mysql\Schema;

class m170206_160050_content_rel extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // ADD META TAGS.
        // <link rel="alternate" hreflang="en" href="/en/apartments/barcelona/">
        $this->createTable('{{%content_relationship}}', [
            'id'         => Schema::TYPE_INTEGER . '(11) NOT NULL AUTO_INCREMENT',
            'content_id' => $this->integer()
                                 ->notNull(),
            'language'   => $this->string(16)
                                 ->notNull(),
            'rel'        => $this->string(512)
                                 ->notNull(),
            'hreflang'   => $this->string(16)
                                 ->notNull(),
            'href'       => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY (`id`, `content_id`, `language`)',
        ]);

        $this->addForeignKey('fk_content_relationship_content_source', '{{%content_relationship}}', 'content_id', '{{%content_source}}', 'id',
            'cascade', 'cascade');
    }


    public function down()
    {
        echo "m170206_160050_content_rel cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
