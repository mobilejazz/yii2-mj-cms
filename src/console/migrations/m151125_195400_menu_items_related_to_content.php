<?php

use yii\db\Migration;

class m151125_195400_menu_items_related_to_content extends Migration
{

    public function down()
    {
        echo "Nothing to do";
    }

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->addColumn('{{menu_item}}', 'content_id', 'integer default 0 AFTER `class`');
    }
}
