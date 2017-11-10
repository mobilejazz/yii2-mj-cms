<?php

use yii\db\Migration;

class m171109_152137_sort_and_thumbnail extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('content_source', 'thumbnail', $this->string()->after('is_homepage'));
        $this->addColumn('content_source', 'sort', $this->integer(255)->after('is_homepage'));

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('content_source', 'thumbnail');
        $this->dropColumn('content_source', 'sort');
    }

}
