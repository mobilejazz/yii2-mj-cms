<?php

use yii\db\Migration;

class m160201_100700_web_form_submission extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // WEB_FORM_SUBMISSION
        $this->createTable('{{%web_form_submission}}',
            [
                'id'         => $this->primaryKey(),
                'web_form'   => $this->integer(11)->notNull(),
                'language'   => $this->string(16)->notNull(),
                'submission' => $this->text(),
                'created_at' => $this->integer(11),
                'updated_at' => $this->integer(11),
            ],
            $tableOptions);

        $this->addForeignKey('fk_web_form_submission_web_form', '{{%web_form_submission}}', 'web_form', '{{%web_form}}', 'id', 'cascade', 'cascade');
    }

    public function down()
    {
        echo "Do nothing";
    }
}
