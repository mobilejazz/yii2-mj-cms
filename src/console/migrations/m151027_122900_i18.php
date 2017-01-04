<?php

use yii\db\Migration;
use yii\db\Schema;

class m151027_122900_i18 extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        /**
         * THIS SCRIPT SHOULD DO THE FOLLOWING:
         *
         * CREATE TABLE source_message (
         * id INTEGER PRIMARY KEY AUTO_INCREMENT,
         * category VARCHAR(32),
         * message TEXT
         * );
         *
         * CREATE TABLE message (
         * id INTEGER,
         * language VARCHAR(16),
         * translation TEXT,
         * PRIMARY KEY (id, language),
         * CONSTRAINT fk_message_source_message FOREIGN KEY (id)
         * REFERENCES source_message (id) ON DELETE CASCADE ON UPDATE RESTRICT
         * );
         */

        // SOURCE_MESSAGE
        $this->createTable('source_message', [
            'id' => Schema::TYPE_PK,
            'category' => Schema::TYPE_STRING . '(32) NOT NULL',
            'message' => Schema::TYPE_TEXT
        ], $tableOptions);

        // MESSAGE
        $this->createTable('message', [
            'id' => Schema::TYPE_INTEGER . '(11) NOT NULL AUTO_INCREMENT',
            'language' => Schema::TYPE_STRING . '(16) NOT NULL',
            'translation' => Schema::TYPE_TEXT,
            'PRIMARY KEY (`id`, `language`)'
        ]);
        $this->addForeignKey('fk_message_source_message', 'message', 'id', 'source_message', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        echo "m151027_122900_i18 cannot be reverted.\n";
    }
}
