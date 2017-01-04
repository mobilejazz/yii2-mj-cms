<?php

use yii\db\Migration;
use yii\db\Schema;

class m160915_153012_introduce_settings_model extends Migration
{
    public function up()
    {

        $this->createTable('setting', [
            'id' => $this->string(128),
            'value' => $this->string(128),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11)
        ]);

        $this->addPrimaryKey('settings_key', 'setting', 'id');
        
    }

    public function down()
    {

        $this->dropTable('setting');

       return true;


    }

}
