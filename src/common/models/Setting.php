<?php

namespace mobilejazz\yii2\cms\common\models;

use mobilejazz\yii2\cms\common\components\TimeStampActiveRecord;

/**
 * This is the base-model class for table "settings".
 *
 * @property string  $id
 * @property string  $value
 * @property integer $created_at
 * @property integer $updated_at
 * @property string  $aliasModel
 */
class Setting extends TimeStampActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'setting';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'id' ], 'required' ],
            [ [ 'id', 'value' ], 'string', 'max' => 128 ]
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'value'      => 'Value',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}
