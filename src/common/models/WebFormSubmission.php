<?php

namespace mobilejazz\yii2\cms\common\models;

use mobilejazz\yii2\cms\common\components\TimeStampActiveRecord;
use yii\base\DynamicModel;
use yii\data\ArrayDataProvider;

/**
 * This is the base-model class for table "web_form_submission".
 *
 * @property integer $id
 * @property integer $web_form
 * @property string  $language
 * @property string  $submission
 * @property integer $exported
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property WebForm $webForm
 */
class WebFormSubmission extends TimeStampActiveRecord
{

    public $script;

    public $fields;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'web_form_submission';
    }


    /**
     * @param $web_form
     * @param $submissions
     *
     * @return ArrayDataProvider
     */
    public static function getDataToArray($web_form, $submissions)
    {
        if (empty($submissions) || !isset($web_form))
        {
            return null;
        }

        $f = [];
        foreach ($submissions as $submission)
        {
            $model = new DynamicModel();
            $data  = json_decode($submission->fields, true);
            foreach ($data as $field)
            {
                $atr_name  = substr(strip_tags(stripslashes(strtolower($field[ 'field_name' ]))), 0, 10);
                $atr_value = stripslashes(strip_tags($field[ 'user_response' ]));
                $model->defineAttribute($atr_name, $atr_value);
            }
            $model->defineAttribute(\Yii::t('backend', 'Date'), date(DATE_ATOM, $submission->created_at));
            $model->defineAttribute('id', $submission->id);
            $model->defineAttribute('web_form', $web_form);
            $f[] = $model;
        }
        unset($submission, $submissions, $field, $fields, $data);

        $dp = new ArrayDataProvider([
            'allModels'  => $f,
            'pagination' => false,
        ]);

        return $dp;
    }


    public static function removeElementWithValue($array, $keys)
    {
        foreach ($array as $subKey => $subArray)
        {
            foreach ($subArray as $k => $v)
            {
                if (in_array($k, $keys))
                {
                    unset($array[ $subKey ][ $k ]);
                }
            }
        }

        return $array;
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'web_form', 'language' ], 'required' ],
            [ [ 'web_form', 'exported', 'created_at', 'updated_at' ], 'integer' ],
            [ [ 'submission' ], 'string' ],
            [ [ 'language' ], 'string', 'max' => 16 ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => \Yii::t('backend', 'ID'),
            'web_form'   => \Yii::t('backend', 'Web Form'),
            'language'   => \Yii::t('backend', 'Language'),
            'submission' => \Yii::t('backend', 'Submission'),
            'exported'   => \Yii::t('backend', 'Exported'),
            'created_at' => \Yii::t('backend', 'Created At'),
            'updated_at' => \Yii::t('backend', 'Updated At'),
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWebForm()
    {
        return $this->hasOne(WebForm::className(), [ 'id' => 'web_form' ]);
    }


    public function getMailSubject()
    {
        return '[' . \Yii::$app->name . '] ' . $this->decodeWebForm() . ' [' . Locale::getCurrent() . ']';
    }


    public function decodeWebForm()
    {
        return $this->decodedSubmission()[ 'web_form' ];
    }


    public function decodedSubmission()
    {
        return json_decode($this->submission, true);
    }


    public function afterFind()
    {
        $this->trigger(self::EVENT_AFTER_FIND);
        $this->loadData();
    }


    public function loadData()
    {
        $this->script = $this->decodedScript();
        $this->fields = $this->decodedFields();
    }


    public function decodedScript()
    {
        return $this->decodedSubmission()[ 'web_form_submit_script' ];
    }


    public function decodedFields()
    {
        $fields = $this->decodedSubmission()[ 'fields' ];

        return json_encode($fields, JSON_PRETTY_PRINT);
    }


    public function decodedLanguage()
    {
        return ucfirst($this->decodedSubmission()[ 'web_form_language' ]);
    }


    public function decodedScriptBoolean()
    {
        return boolval(!empty($this->decodedScript()));
    }


    public function decodedMailCount()
    {
        $count = count($this->decodedMails());

        return ucfirst(\Yii::t('backend', '{n, spellout}', [ 'n' => $count, ]));
    }


    public function decodedMails()
    {
        return $this->decodedSubmission()[ 'web_form_mails' ];
    }


    public function decodedFieldsCount()
    {
        $count = count($this->decodedSubmission()[ 'fields' ]);

        return ucfirst(\Yii::t('backend', '{n, spellout}', [ 'n' => $count, ]));
    }


    public function decodedDescription()
    {
        return ucfirst($this->decodedSubmission()[ 'web_form_description' ]);
    }


    public function decodedMessage()
    {
        return ucfirst($this->decodedSubmission()[ 'web_form_submit_message' ]);
    }


    public function behaviors()
    {
        return [
            'encryption' => [
                'class'      => 'mobilejazz\yii2\cms\common\modules\encrypt\behaviors\EncryptionBehavior',
                'attributes' => [
                    'submission'
                ],
            ],
        ];
    }
}
