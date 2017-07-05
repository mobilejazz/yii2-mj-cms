<?php

use yii\db\Migration;

class m170705_095702_webform extends Migration
{
    public function safeUp()
    {

    }

    public function safeDown()
    {
        echo "m170705_095702_webform cannot be reverted.\n";

        return false;
    }

    public function up()
    {
        $this->addColumn("web_form_row", "internal_name", "string");
    }

    public function down()
    {
        echo "m170705_095702_webform cannot be reverted.\n";

        return false;
    }
}
