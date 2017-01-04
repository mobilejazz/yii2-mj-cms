<?php

namespace mobilejazz\yii2\cms\common\components;

use yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class TimeStampActiveRecord extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class'      => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => [ 'created_at', 'updated_at' ],
                    ActiveRecord::EVENT_BEFORE_UPDATE => [ 'updated_at' ],
                ],
                'value'      => time(),
            ],
        ];
    }


    public function fields()
    {
        $fields = parent::fields();

        // remove updated_at field
        unset($fields[ 'updated_at' ]);

        return $fields;
    }
}