<?php

namespace mobilejazz\yii2\cms\common\models;

use mobilejazz\yii2\cms\common\components\TimeStampActiveRecord;
use yii;
use yii\db\ActiveQuery;
use yii\validators\EmailValidator;

/**
 * This is the base-model class for table "web_form_detail".
 *
 * @property integer $id
 * @property integer $web_form
 * @property string  $language
 * @property string  $title
 * @property string  $mail
 * @property integer $send_mail
 * @property string  $description
 * @property string  $script
 * @property string  $message
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property WebForm $webForm
 */
class WebFormDetail extends TimeStampActiveRecord
{

    /**
     * @var array virtual attribute for keeping emails
     */
    public $emails;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'web_form_detail';
    }


    public function init()
    {
        parent::init();
        $this->emails = json_decode($this->mail);
    }


    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'web_form', 'language' ], 'required' ],
            [ [ 'web_form', 'created_at', 'updated_at', 'send_mail' ], 'integer' ],
            [ [ 'language' ], 'string', 'max' => 16, ],
            [ [ 'description', 'script', 'message' ], 'string' ],
            [ [ 'title', 'mail' ], 'string', 'max' => 255 ],
            [ 'emails', 'validateEmails' ],
        ];
    }


    /**
     * Email validation.
     *
     * @param $attribute
     */
    public function validateEmails($attribute)
    {
        // Email validation is only required if
        if ($this->send_mail)
        {
            $items = $this->$attribute;
            if (!is_array($items))
            {
                $items = [];
            }
            foreach ($items as $index => $item)
            {
                $validator = new EmailValidator();
                $error     = null;
                $validator->validate($item, $error);
                if (!empty($error))
                {
                    $key = $attribute . '[' . $index . ']';
                    $this->addError($key, $error);
                }
            }
        }
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => Yii::t('backend', 'ID'),
            'web_form'    => Yii::t('backend', 'Web Form'),
            'language'    => Yii::t('backend', 'Language'),
            'title'       => Yii::t('backend', 'Title'),
            'mail'        => Yii::t('backend', 'Mail'),
            'send_mail'   => Yii::t('backend', 'Send Email After Submission'),
            'description' => Yii::t('backend', 'Description'),
            'script'      => Yii::t('backend', 'Javascript to Execute'),
            'message'     => Yii::t('backend', 'Thank you Message'),
            'created_at'  => Yii::t('backend', 'Created At'),
            'updated_at'  => Yii::t('backend', 'Updated At'),
        ];
    }


    /**
     * @return ActiveQuery|WebForm
     */
    public function getWebForm()
    {
        return $this->hasOne(WebForm::className(), [ 'id' => 'web_form' ]);
    }

}
