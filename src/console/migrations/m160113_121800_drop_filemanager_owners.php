<?php

use yii\db\Migration;

class m160113_121800_drop_filemanager_owners extends Migration
{
    public function up()
    {
        $this->dropTable("filemanager_owners");
    }

    public function down()
    {
        echo "Do nothing";
    }
}
