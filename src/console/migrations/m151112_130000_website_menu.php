<?php

use yii\db\Migration;
use yii\db\Schema;

class m151112_130000_website_menu extends Migration
{

    public function down()
    {
        $this->dropTable('{{%menu_item_translation}}');
        $this->dropTable('{{%menu_item}}');
        $this->dropTable('{{%menu}}');
    }

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // MENU
        $this->createTable('{{%menu}}',
            [
                'id'         => Schema::TYPE_INTEGER . '(11) NOT NULL AUTO_INCREMENT',
                'key'        => $this->string(),
                'class'      => $this->string(),
                'created_at' => $this->integer(),
                'updated_at' => $this->integer(),
                'PRIMARY KEY (`id`, `key`)',
            ],
            $tableOptions);

        // MENU ITEMS
        $this->createTable('{{%menu_item}}',
            [
                'id'         => Schema::TYPE_PK,
                'menu_id'    => $this->integer()->notNull(),
                'parent'     => $this->integer(),
                'order'      => $this->integer()->notNull(),
                'target'     => $this->integer(),
                'class'      => $this->string(),
                'created_at' => $this->integer(),
                'updated_at' => $this->integer(),
            ],
            $tableOptions);

        // MENU ITEMS TRANSLATIONS
        $this->createTable('{{%menu_item_translation}}',
            [
                'id'           => Schema::TYPE_PK,
                'menu_item_id' => $this->integer()->notNull(),
                'language'     => $this->string(16)->notNull(),
                'title'        => $this->string(45),
                'link'         => $this->string(),
                'created_at'   => $this->integer(),
                'updated_at'   => $this->integer(),
            ],
            $tableOptions);

        $this->addForeignKey('fk_menu_items_menu', '{{%menu_item}}', 'menu_id', '{{%menu}}', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_menu_item_translation_menu_item', '{{%menu_item_translation}}', 'menu_item_id', '{{%menu_item}}', 'id', 'cascade', 'cascade');
    }
}
