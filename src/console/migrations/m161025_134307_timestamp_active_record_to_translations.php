<?php

use yii\db\Migration;

class m161025_134307_timestamp_active_record_to_translations extends Migration
{

    public function up()
    {
        $this->addColumn("i18n_message", "updated_at", "INT DEFAULT 0");
        $this->addColumn("i18n_message", "created_at", "INT DEFAULT 0");
    }


    public function down()
    {
        echo "m161025_134307_timestamp_active_record_to_translations cannot be reverted.\n";

        return false;
    }

}
