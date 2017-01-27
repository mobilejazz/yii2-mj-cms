<?php

use yii\db\Migration;

class m170127_185424_webform_class extends Migration
{

    public function up()
    {
        $this->addColumn("web_form_detail", "css_class", "string AFTER send_mail");
    }


    public function down()
    {
        echo "m170127_185424_webform_class cannot be reverted.\n";

        return false;
    }
}
