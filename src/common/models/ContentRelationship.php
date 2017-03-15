<?php

namespace mobilejazz\yii2\cms\common\models;

use mobilejazz\yii2\cms\common\components\TimeStampActiveRecord;
use Yii;

/**
 * This is the base-model class for table "content_relationship".
 *
 * @property integer $id
 * @property integer $content_id
 * @property string  $language
 * @property string  $rel
 * @property string  $hreflang
 * @property string  $href
 * @property integer $created_at
 * @property integer $updated_at
 * @property string  $aliasModel
 */
class ContentRelationship extends TimeStampActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'content_relationship';
    }


    /**
     * Alias name of table for crud viewsLists all Area models.
     * Change the alias name manual if needed later
     *
     * @param bool $plural
     *
     * @return string
     */
    public function getAliasModel($plural = false)
    {
        if ($plural)
        {
            return Yii::t('backend', 'ContentRelationships');
        }
        else
        {
            return Yii::t('backend', 'ContentRelationship');
        }
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'content_id', 'language', 'rel', 'hreflang', 'href' ], 'required' ],
            [ [ 'content_id', 'created_at', 'updated_at' ], 'integer' ],
            [ [ 'language' ], 'string', 'max' => 16 ],
            [ [ 'rel' ], 'string', 'max' => 512 ],
            [ [ 'hreflang' ], 'string', 'max' => 16 ],
            [ [ 'href' ], 'string' ]
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => \Yii::t('backend', 'ID'),
            'content_id' => \Yii::t('backend', 'Content ID'),
            'language'   => \Yii::t('backend', 'Language'),
            'rel'        => \Yii::t('backend', 'Relationship'),
            'hreflang'   => \Yii::t('backend', 'Href Lang'),
            'href'       => \Yii::t('backend', 'Href'),
            'created_at' => \Yii::t('backend', 'Created At'),
            'updated_at' => \Yii::t('backend', 'Updated At'),
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return array_merge(parent::attributeHints(), [
            'id'         => \Yii::t('backend', 'ID'),
            'content_id' => \Yii::t('backend', 'Content ID'),
            'language'   => \Yii::t('backend', 'Language'),
            'rel'        => \Yii::t('backend', 'Relationship'),
            'hreflang'   => \Yii::t('backend', 'Href Lang'),
            'href'       => \Yii::t('backend', 'Href'),
            'created_at' => \Yii::t('backend', 'Created At'),
            'updated_at' => \Yii::t('backend', 'Updated At'),
        ]);
    }

}
