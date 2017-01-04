<?php

namespace mobilejazz\yii2\cms\backend\modules\i18n\models;

use Yii;

/**
 * This is the model class for table "{{%i18n_source_message}}".
 *
 * @property integer       $id
 * @property string        $category
 * @property string        $message
 *
 * @property I18nMessage[] $i18nMessages
 */
class I18nSourceMessage extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%i18n_source_message}}';
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'       => 'ID',
            'category' => 'Category',
            'message'  => 'Message',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getI18nMessages()
    {
        return $this->hasMany(I18nMessage::className(), [ 'id' => 'id' ]);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'message' ], 'string' ],
            [ [ 'category' ], 'string', 'max' => 32 ],
        ];
    }
}
