<?php

namespace mobilejazz\yii2\cms\common\models;

use mobilejazz\yii2\cms\common\components\TimeStampActiveRecord;
use yii;

/**
 * This is the base-model class for table "web_form_row".
 *
 * @property integer           $id
 * @property integer           $web_form
 * @property string            $language
 * @property string            $legend
 * @property string            $internal_name
 * @property integer           $order
 * @property integer           $created_at
 * @property integer           $updated_at
 *
 * @property WebForm           $webForm
 * @property WebFormRowField[] $webFormRowFields
 */
class WebFormRow extends TimeStampActiveRecord
{

    /**
     * @param WebFormRow $row
     *
     * @return mixed
     */
    public static function getMaxOrder($row)
    {
        $tr = self::find()
                  ->where([
                      'web_form' => $row->web_form,
                      'language' => $row->language,
                  ])
                  ->max('`order`');
        if (!isset($tr) || $tr == null)
        {
            $tr = 0;
        }

        return $tr;
    }


    /**
     * @param integer $id
     * @param string  $lang
     * @param integer $order
     *
     * @return WebFormRow
     */
    public static function create($id, $lang, $order, $internal_name = null)
    {
        // Define the new Row.
        /** @var WebFormRow $row */
        $row             = new WebFormRow();
        $row->web_form   = $id;
        $row->language   = $lang;
        $row->order      = $order;
        $row->created_at = time();
        $row->updated_at = time();
        $row->internal_name = $internal_name;
        $row->save();

        return $row;
    }


    /**
     * Re-Orders all the rows of a given form.
     *
     * @param WebForm $form
     */
    public static function sanitizeOrder($form)
    {
        /** @var WebFormRow[] $rows */
        $rows = $form->getOrderedWebFormRows(Yii::$app->language);

        foreach ($rows as $index => $row)
        {
            $row->order = $index + 1;
            $row->save();
        }
    }


    /**
     * Set a new order to a given row.
     *
     * @param WebFormRow $row
     * @param integer    $order
     */
    public static function setOrder($row, $order)
    {
        $row->order = $order;
        $row->save();
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'web_form_row';
    }


    /**
     * Returns the Ordered Fields for this particular Row and a given language
     *
     * @param $language
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getOrderedWebFormRowFields($language)
    {
        /** @var WebFormRowField[] fields */
        return WebFormRowField::find()
                              ->where([ 'web_form_row' => $this->id, 'language' => $language ])
                              ->orderBy([ 'order' => SORT_ASC ])
                              ->all();
    }


    public function hasLegend()
    {
        return strlen($this->legend) > 0 ? true : false;
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'web_form', 'language', 'order' ], 'required' ],
            [ [ 'web_form', 'order', 'created_at', 'updated_at' ], 'integer' ],
            [ [ 'language' ], 'string', 'max' => 16 ],
            [ [ 'legend' ], 'string', 'max' => 255 ],
            [ [ 'internal_name' ], 'string', 'max' => 255 ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'web_form'   => 'Web Form',
            'language'   => 'Language',
            'legend'     => 'Legend',
            'internal_name' => 'Internal Name',
            'order'      => 'Order',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWebForm()
    {
        return $this->hasOne(WebForm::className(), [ 'id' => 'web_form' ]);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWebFormRowFields()
    {
        return $this->hasMany(WebFormRowField::className(), [ 'web_form_row' => 'id' ]);
    }

}
