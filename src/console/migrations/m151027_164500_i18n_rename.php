<?php

use yii\db\Migration;

class m151027_164500_i18n_rename extends Migration
{
    public function up()
    {
        $this->renameTable("message", "i18n_message");
        $this->renameTable("source_message", "i18n_source_message");
    }

    public function down()
    {
        echo "m151027_122900_i18 cannot be reverted.\n";
    }
}
