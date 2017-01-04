<?php

use yii\db\Migration;

class m160120_133200_web_forms extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // WEB_FORM
        $this->createTable('{{%web_form}}',
            [
                'id'         => $this->primaryKey(),
                'author_id'  => $this->integer(11),
                'updater_id' => $this->integer(11),
                'created_at' => $this->integer(11),
                'updated_at' => $this->integer(11),
            ],
            $tableOptions);

        // WEB_FORM_ROW
        $this->createTable('{{%web_form_row}}',
            [
                'id'         => $this->primaryKey(),
                'web_form'   => $this->integer(11)->notNull(),
                'language'   => $this->string(16)->notNull(),
                'legend'     => $this->string(255),
                'order'      => $this->integer(11)->notNull(),
                'created_at' => $this->integer(11),
                'updated_at' => $this->integer(11),
            ],
            $tableOptions);

        // WEB_FORM_DETAILS
        $this->createTable('{{%web_form_detail}}',
            [
                'id'          => $this->primaryKey(),
                'web_form'    => $this->integer(11)->notNull(),
                'language'    => $this->string(16)->notNull(),
                'title'       => $this->string(255),
                'mail'        => $this->string(255),
                'description' => $this->text(),
                'script'      => $this->text(),
                'message'     => $this->text(),
                'created_at'  => $this->integer(11),
                'updated_at'  => $this->integer(11),
            ],
            $tableOptions);

        // WEB_FORM_FIELD
        $this->createTable('{{%web_form_row_field}}',
            [
                'id'           => $this->primaryKey(),
                'web_form_row' => $this->integer(11)->notNull(),
                'type'         => $this->string()->notNull(),
                'language'     => $this->string(16)->notNull(),
                'order'        => $this->integer(11)->notNull(),
                'required'     => $this->integer()->notNull()->defaultValue(0),
                'placeholder'  => $this->string(255),
                'hint'         => $this->string(255),
                'created_at'   => $this->integer(11),
                'updated_at'   => $this->integer(11),
            ],
            $tableOptions);

        $this->addForeignKey('fk_web_form_row_field_web_form_row', '{{%web_form_row_field}}', 'web_form_row', '{{%web_form_row}}', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_web_form_row_web_form', '{{%web_form_row}}', 'web_form', '{{%web_form}}', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_web_form_detail_web_form', '{{%web_form_detail}}', 'web_form', '{{%web_form}}', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_web_form_author', '{{%web_form}}', 'author_id', '{{%user}}', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_web_form_updater', '{{%web_form}}', 'updater_id', '{{%user}}', 'id', 'set null', 'cascade');
    }

    public function down()
    {
        echo "Do nothing";
    }
}
