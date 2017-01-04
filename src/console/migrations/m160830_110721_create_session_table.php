<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation for table `session`.
 */
class m160830_110721_create_session_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if(!Yii::$app->db->schema->getTableSchema('session', true)){
            $this->createTable('session', [
                'id' => $this->char(40) . ' NOT NULL PRIMARY KEY',
                'expire' => Schema::TYPE_INTEGER,
                'data' => Schema::TYPE_TEXT
            ]);

        }

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('session');
    }
}