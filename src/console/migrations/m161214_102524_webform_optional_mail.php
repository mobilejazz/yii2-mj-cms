<?php

use yii\db\Migration;

class m161214_102524_webform_optional_mail extends Migration
{

    public function up()
    {
        $this->addColumn("web_form_detail", "send_mail", "INT DEFAULT 0 AFTER mail");
    }


    public function down()
    {
        echo "m161214_102524_webform_optional_mail cannot be reverted.\n";

        return false;
    }
}
