<?php

namespace mobilejazz\yii2\cms\common\models;

use yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the base-model class for table "web_form".
 *
 * @property integer             $id
 * @property integer             $author_id
 * @property integer             $updater_id
 * @property integer             $created_at
 * @property integer             $updated_at
 *
 * @property User                $updater
 * @property User                $author
 * @property WebFormDetail[]     $webFormDetails
 * @property WebFormRow[]        $webFormRows
 * @property WebFormSubmission[] $webFormSubmissions
 */
class WebForm extends ActiveRecord
{

    public static function asMap()
    {
        $array_to_return = [];
        /** @var WebForm[] $web_forms */
        $web_forms = self::find()
                         ->all();
        foreach ($web_forms as $wf)
        {
            $array_to_return[ strval($wf->id) ] = $wf->getTitle();
        }

        return $array_to_return;
    }


    /**
     * Returns the title of the current WebForm.
     * @return String
     */
    public function getTitle()
    {
        /** @var WebFormDetail $model */
        $model = $this->hasOne(WebFormDetail::className(), [ 'web_form' => 'id', ])
                      ->andWhere([ 'language' => Yii::$app->language ])
                      ->orderBy([ 'updated_at' => SORT_DESC ])
                      ->one();

        return $model->title;
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'web_form';
    }


    /**
     * @param $language
     *
     * @return WebFormDetail|null|ActiveRecord
     */
    public function getCurrentDetails($language)
    {
        return WebFormDetail::find()
                            ->where([ 'web_form' => $this->id, 'language' => $language ])
                            ->orderBy([ 'updated_at' => SORT_DESC ])
                            ->one();
    }


    /**
     * @param $language
     *
     * @return WebFormRow[]
     */
    public function getOrderedWebFormRows($language)
    {
        return $this->getWebFormRows()
                    ->andWhere([ 'language' => $language ])
                    ->orderBy([ 'order' => SORT_ASC, ])
                    ->all();
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWebFormRows()
    {
        return $this->hasMany(WebFormRow::className(), [ 'web_form' => 'id' ]);
    }


    /**
     * Run some checks to see if this Web Form is used inside any content, if it is, WE CAN NOT DELETE IT.
     * @return bool
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete())
        {
            // Check if the form is inside some content.
            $count = ComponentField::find()
                                   ->where([ 'type' => 'form-dropdown', 'text' => $this->id ])
                                   ->count();
            // If this is used else, do not allow to delete this form.
            if ($count > 0)
            {
                Yii::$app->getSession()
                         ->setFlash('error', Yii::t('backend', 'This form is used in some Content in the website, therefore it can not be deleted.'));

                return false;
            }

            // Check if the form has submissions.
            if ($this->getWebFormSubmissions()
                     ->count() > 0
            )
            {
                Yii::$app->getSession()
                         ->setFlash('error', Yii::t('backend', 'This form is has submissions, therefore it can not be deleted.'));

                return false;
            }

            return true;
        }
        else
        {
            return false;
        }
    }


    public function getWebFormSubmissions()
    {
        return $this->hasMany(WebFormSubmission::className(), [ 'web_form' => 'id' ]);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'author_id', 'updater_id', 'created_at', 'updated_at' ], 'integer' ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class'              => BlameableBehavior::className(),
                'createdByAttribute' => 'author_id',
                'updatedByAttribute' => 'updater_id',
            ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'author_id'  => 'Author ID',
            'updater_id' => 'Updater ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdater()
    {
        return $this->hasOne(User::className(), [ 'id' => 'updater_id' ]);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), [ 'id' => 'author_id' ]);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWebFormDetails()
    {
        return $this->hasMany(WebFormDetail::className(), [ 'web_form' => 'id' ]);
    }

}
