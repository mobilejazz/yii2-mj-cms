<?php

use yii\db\Schema;
use yii\db\Migration;

class m130524_204400_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => Schema::TYPE_PK,
            'email' => Schema::TYPE_STRING . ' NOT NULL',
            'auth_key' => Schema::TYPE_STRING . '(32) NOT NULL',
            'password_hash' => Schema::TYPE_STRING . ' NOT NULL',
            'password_reset_token' => Schema::TYPE_STRING,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
			'last_name' => Schema::TYPE_STRING . ' NOT NULL',
			'role' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',
			'picture' => Schema::TYPE_STRING . ' NULL',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',
            'created_at' => Schema::TYPE_INTEGER . ' NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NULL',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
