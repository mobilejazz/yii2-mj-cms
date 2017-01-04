<?php

namespace mobilejazz\yii2\cms\backend\modules\i18n\models;

use mobilejazz\yii2\cms\common\components\TimeStampActiveRecord;
use Yii;

/**
 * This is the model class for table "{{%i18n_message}}".
 *
 * @property integer           $id
 * @property string            $language
 * @property string            $translation
 * @property string            $sourceMessage
 * @property string            $category
 *
 * @property I18nSourceMessage $sourceMessageModel
 */
class I18nMessage extends TimeStampActiveRecord
{

    public $category;

    public $sourceMessage;


    public static function countMissingTranslations()
    {
        return I18nMessage::find()
                          ->where([ 'language' => Yii::$app->language, 'translation' => null ])
                          ->count();
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%i18n_message}}';
    }


    public function afterFind()
    {
        $this->sourceMessage = $this->sourceMessageModel ? $this->sourceMessageModel->message : null;
        $this->category      = $this->sourceMessageModel ? $this->sourceMessageModel->category : null;

        return parent::afterFind();
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'language'      => 'Language',
            'translation'   => 'Translation',
            'sourceMessage' => 'Source Message',
            'category'      => 'Category',
            'created_at'    => 'Created At',
            'updated_at'    => 'Updated At'
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSourceMessageModel()
    {
        return $this->hasOne(I18nSourceMessage::className(), [ 'id' => 'id' ]);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'id', 'language' ], 'required' ],
            [ [ 'created_at', 'updated_at' ], 'integer' ],
            [ [ 'id' ], 'exist', 'targetClass' => I18nSourceMessage::className(), 'targetAttribute' => 'id' ],
            [ [ 'translation' ], 'string' ],
            [ [ 'language' ], 'string', 'max' => 16 ],
            [ [ 'language' ], 'unique', 'targetAttribute' => [ 'id', 'language' ] ],
        ];
    }
}
