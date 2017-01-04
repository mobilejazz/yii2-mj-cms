<?php

use yii\db\Migration;
use yii\db\Schema;

class m151116_200700_locale extends Migration
{

    public function down()
    {
        $this->dropTable('{{%locale}}');
    }

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // MENU
        $this->createTable('{{%locale}}',
            [
                'id'         => Schema::TYPE_INTEGER . '(11) NOT NULL AUTO_INCREMENT',
                'lang'       => $this->string(),
                'label'      => $this->string(),
                'default'    => $this->boolean(),
                'created_at' => $this->integer(),
                'updated_at' => $this->integer(),
                'PRIMARY KEY (`id`, `lang`)',
            ],
            $tableOptions);

        $this->insert('{{%locale}}',
            [
                'id'         => 1,
                'lang'       => 'en',
                'label'      => 'English',
                'default'    => true,
                'created_at' => time(),
                'updated_at' => time(),
            ]);
    }
}
