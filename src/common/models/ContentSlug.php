<?php

namespace mobilejazz\yii2\cms\common\models;

use Exception;
use mobilejazz\yii2\cms\common\behaviors\SluggableBehavior;
use yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the base-model class for table "content_slug".
 *
 * @property integer       $id
 * @property integer       $content_id
 * @property string        $language
 * @property string        $slug
 * @property string        $title
 * @property integer       $system
 * @property integer       $created_at
 * @property integer       $updated_at
 *
 * @property ContentSource $content
 */
class ContentSlug extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'content_slug';
    }


    public function activate()
    {
        $this->updated_at = time();
        $this->save();
    }


    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $this->content->updater_id = Yii::$app->user->id;
        $this->content->updated_at = time();
        $this->content->save();
    }


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        if (Yii::$app->request->isPost)
        {
            try
            {
                $s    = $_POST[ 'ContentSlug' ][ 'slug' ];
                $attr = isset($s) && strlen($s) > 0 ? 'slug' : 'title';

                return [
                    TimestampBehavior::className(),
                    [
                        'class'           => SluggableBehavior::className(),
                        'attribute'       => $attr,
                        'slugAttribute'   => 'slug',
                        'immutable'       => false,
                        'ensureUnique'    => true,
                        'uniqueValidator' => [
                            'targetAttribute' => [ 'slug', 'language' ],
                            'message'         => 'This language / slug combination has already been used, please use a new one.',
                        ],
                    ],
                ];
            }
            catch (Exception $e)
            {
                return parent::behaviors();
            }
        }

        return parent::behaviors();
    }


    /**
     * Checks if this is the current Slug for this language.
     * @return bool
     */
    public function isActive()
    {
        $current = $this->content->getCurrentSlug($this->language);
        if ($this->id == $current->id)
        {
            return true;
        }

        return false;
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('app', 'ID'),
            'content_id' => Yii::t('app', 'Content ID'),
            'language'   => Yii::t('app', 'Language'),
            'slug'       => Yii::t('app', 'Slug'),
            'title'      => Yii::t('app', 'Title'),
            'system'     => Yii::t('app', 'System'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContent()
    {
        return $this->hasOne(ContentSource::className(), [ 'id' => 'content_id' ]);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'content_id', 'language', 'title' ], 'required' ],
            [
                'slug',
                'unique',
                'targetAttribute' => [ 'slug', 'language' ],
                'message'         => 'This language / slug combination has already been used, please use a new one.',
            ],
            [ [ 'content_id', 'system', 'created_at', 'updated_at' ], 'integer' ],
            [ [ 'language' ], 'string', 'max' => 16 ],
            [ [ 'slug' ], 'string', 'max' => 1024 ],
            [ [ 'title' ], 'string', 'max' => 512 ],
        ];
    }

}
