<?php

use yii\db\Schema;
use yii\db\Migration;

class m150919_015100_user_profile extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('user_profile', [
            'id' => Schema::TYPE_PK,
            'about' => Schema::TYPE_STRING,
            'created_at' => Schema::TYPE_INTEGER . ' NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NULL',
        ], $tableOptions);


		$this->addForeignKey('user_profile_fk', 'user_profile', 'id', 'user', 'id', 'CASCADE', 'CASCADE');

    }

    public function down()
    {
		$this->dropTable('user_profile');
    }
}
