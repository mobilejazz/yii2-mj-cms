<?php

use yii\db\Migration;
use yii\db\Schema;

class m160112_173402_create_filemanager_owners_table extends Migration
{
    public function up()
    {
        $this->createTable('filemanager_owners',
            [
                'mediafile_id'    => Schema::TYPE_INTEGER . ' NOT NULL',
                'owner_id'        => Schema::TYPE_INTEGER . ' NOT NULL',
                'owner'           => Schema::TYPE_STRING . '(191) NOT NULL',
                'owner_attribute' => Schema::TYPE_STRING . '(191) NOT NULL',
                'PRIMARY KEY (`mediafile_id`, `owner_id`, `owner`, `owner_attribute`)',
            ]);
    }

    public function down()
    {
        $this->dropTable('filemanager_owners');
    }
}
