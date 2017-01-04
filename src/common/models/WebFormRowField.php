<?php

namespace mobilejazz\yii2\cms\common\models;

use yii;
use yii\db\ActiveRecord;

/**
 * This is the base-model class for table "web_form_row_field".
 *
 * @property integer    $id
 * @property integer    $web_form_row
 * @property string     $type
 * @property string     $language
 * @property integer    $order
 * @property integer    $required
 * @property integer    $is_sensitive
 * @property string     $name
 * @property string     $placeholder
 * @property string     $hint
 * @property string     $error_message
 * @property integer    $created_at
 * @property integer    $updated_at
 *
 * @property WebFormRow $webFormRow
 */
class WebFormRowField extends ActiveRecord
{

    /** @var String $value User Data Input */
    public $text;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'web_form_row_field';
    }


    /**
     * @param $id
     * @param $lang
     * @param $order
     *
     * @return WebFormRowField
     */
    public static function create($id, $lang, $order)
    {
        $field               = new WebFormRowField();
        $field->web_form_row = $id;
        $field->type         = 'text-box';
        $field->language     = $lang;
        $field->order        = $order;
        $field->required     = 0;
        $field->is_sensitive = 0;
        $field->created_at   = time();
        $field->updated_at   = time();
        $field->save(false);

        return $field;
    }


    /**
     * @param WebFormRow $row
     *
     * @return int|mixed
     */
    public static function getMaxOrder($row)
    {
        $tr = self::find()
                  ->where([
                      'web_form_row' => $row->id,
                      'language'     => $row->language,
                  ])
                  ->max('`order`');
        if (!isset($tr) || $tr == null)
        {
            $tr = 0;
        }

        return $tr;
    }


    /**
     * Re-Orders all the fields of a given row.
     *
     * @param WebFormRow $row
     */
    public static function sanitizeOrder($row)
    {
        /** @var WebFormRowField[] $fields */
        $fields = $row->getOrderedWebFormRowFields(Yii::$app->language);

        foreach ($fields as $index => $field)
        {
            $field->order = $index + 1;
            $field->save();
        }
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        $tr1                  = boolval($this->required) ? [
            [
                [ 'text' ],
                'required',
                'message' => $this->error_message ? $this->error_message : \Yii::t('app', 'The text can not be blank')
            ]
        ] : [];
        $field_rules          = Fields::getRules($this->type);
        $field_rules_required = $field_rules[ 0 ][ 0 ][ 0 ] == "text" && $field_rules[ 0 ][ 1 ] == "required";
        if (!boolval($this->required) && $field_rules_required)
        {
            unset($field_rules[ 0 ]);
            assert($field_rules);
        }
        $tr2 = $field_rules != null && (isset($this->type) && $this->type != null && $this->required) ? $field_rules : [];
        $tr3 = [
            [ [ 'web_form_row', 'type', 'language', 'order', 'name', 'is_sensitive' ], 'required' ],
            [ [ 'web_form_row', 'order', 'required', 'created_at', 'updated_at', 'is_sensitive' ], 'integer' ],
            [ [ 'type', 'placeholder' ], 'string', 'max' => 255 ],
            [ [ 'hint' ], 'string' ],
            [ [ 'language' ], 'string', 'max' => 16 ],
        ];

        $tr4 = $this->type == Fields::FIELD_CHECKBOX && boolval($this->required) ? [
            [
                'text',
                'in',
                'range'   => [ 1 ],
                'message' => $this->error_message ? $this->error_message : \Yii::t('app', 'This is a required checkbox')
            ]
        ] : [];

        $return = array_unique(array_merge($tr1, $tr2, $tr3, $tr4), SORT_REGULAR);;

        return $return;
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => Yii::t('backend', 'ID'),
            'web_form_row' => Yii::t('backend', 'Web Form Row'),
            'type'         => Yii::t('backend', 'Type'),
            'language'     => Yii::t('backend', 'Language'),
            'order'        => Yii::t('backend', 'Order'),
            'required'     => Yii::t('backend', 'Required'),
            'is_sensitive' => Yii::t('backend', 'Sensitive Information?'),
            'name'         => Yii::t('backend', 'Name'),
            'placeholder'  => Yii::t('backend', 'Placeholder'),
            'hint'         => Yii::t('backend', 'Hint'),
            'created_at'   => Yii::t('backend', 'Created At'),
            'updated_at'   => Yii::t('backend', 'Updated At'),
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWebFormRow()
    {
        return $this->hasOne(WebFormRow::className(), [ 'id' => 'web_form_row' ]);
    }
}
