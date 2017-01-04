<?php

use mobilejazz\yii2\cms\common\models\Locale;
use yii\db\Migration;

class m160301_123400_locale_country_codes extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // WEB_FORM_SUBMISSION
        $this->addColumn('{{locale}}', 'country_code', 'string(255) AFTER `lang`');

        // Default locale to GB.
        $gb               = Locale::findOne([ 'id' => 1, ]);
        $gb->country_code = 'gb';
        $gb->save(false);
    }


    public function down()
    {
        echo "Do nothing";
    }
}
