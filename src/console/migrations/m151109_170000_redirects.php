<?php

use yii\db\Migration;
use yii\db\Schema;

class m151109_170000_redirects extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // REDIRECTS.
        $this->createTable('{{%url_redirect}}', [
            'id'               => Schema::TYPE_PK,
            'origin_slug'      => $this->string()->notNull(),
            'destination_slug' => $this->string()->notNull(),
            'created_at'       => $this->integer(),
            'updated_at'       => $this->integer(),
        ]);
    }


    public function safeDown()
    {
        echo "m151109_170000_redirects cannot be reverted \n";
    }
}
