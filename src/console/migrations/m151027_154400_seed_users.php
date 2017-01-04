<?php

use mobilejazz\yii2\cms\common\models\User;
use yii\db\Migration;

class m151027_154400_seed_users extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->insert('{{%user}}', [
            'id'            => 1,
            'email'         => 'admin@example.com',
            'name'          => 'Admin',
            'last_name'     => 'Example',
            'password_hash' => Yii::$app->getSecurity()
                                        ->generatePasswordHash('admin'),
            'auth_key'      => Yii::$app->getSecurity()
                                        ->generateRandomString(),
            'status'        => User::STATUS_ACTIVE,
            'role'          => 20,
            'created_at'    => time(),
            'updated_at'    => time(),
        ]);

        $this->insert('user_profile', [
            'id'         => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert('auth_item', [
            'name'       => 'admin',
            'type'       => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert('auth_assignment', [
            'item_name'  => 'admin',
            'user_id'    => 1,
            'created_at' => time(),
        ]);
    }


    public function down()
    {
        $this->delete('{{%user_profile}}', [
            'user_id' => [ 1 ],
        ]);

        $this->delete('{{%user}}', [
            'id' => [ 1 ],
        ]);
    }
}
