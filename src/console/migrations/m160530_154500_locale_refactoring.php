<?php

use yii\db\Migration;

class m160530_154500_locale_refactoring extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // FROM EN TO EN_GB --> EVERYTHING SHOULD BE LOWERCASE.
        $this->update('component_field', [ 'language' => 'en_gb' ], "language='en'");
        $this->update('content_component', [ 'language' => 'en_gb' ], "language='en'");
        $this->update('content_meta_tag', [ 'language' => 'en_gb' ], "language='en'");
        $this->update('content_slug', [ 'language' => 'en_gb' ], "language='en'");
        $this->update('i18n_message', [ 'language' => 'en_gb' ], "language='en'");
        $this->update('menu_item_translation', [ 'language' => 'en_gb' ], "language='en'");
        $this->update('web_form_detail', [ 'language' => 'en_gb' ], "language='en'");
        $this->update('web_form_row', [ 'language' => 'en_gb' ], "language='en'");
        $this->update('web_form_row_field', [ 'language' => 'en_gb' ], "language='en'");
        $this->update('web_form_submission', [ 'language' => 'en_gb' ], "language='en'");
    }


    public function down()
    {
        echo "Do nothing";
    }
}
