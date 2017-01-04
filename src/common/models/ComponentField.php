<?php

namespace mobilejazz\yii2\cms\common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 *
 * Every Field has an ID defined and it HAS TO BE unique. Also, please, don't change the IDS
 * without explicitly changing them in the database as well.
 *
 * @property integer          $id
 * @property integer          $component_id
 * @property string           $type
 * @property string           $language
 * @property integer          $order
 * @property integer          $required
 * @property integer          $repeatable
 * @property integer          $is_child
 * @property string           $text
 * @property integer          $created_at
 * @property integer          $updated_at
 *
 * @property ContentComponent $component
 */
class ComponentField extends ActiveRecord
{

    /**
     * @param ContentComponent $component
     * @param integer          $key
     * @param string[]         $value
     * @param boolean          $is_child
     * @param integer          $order
     * @param string           $text
     *
     * @return bool true if saved, false otherwise
     */
    public static function create($component, $key, $value, $is_child, $order, $text = "")
    {
        $field               = new ComponentField();
        $field->component_id = $component->id;
        $field->type         = $key;
        $field->language     = $component->language;
        $field->order        = $order;
        $field->required     = intval($value[ 'required' ]);
        $field->repeatable   = intval($value[ 'repeatable' ]);
        $field->is_child     = intval($is_child);
        $field->text         = $text;

        return $field->save(false);
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'component_field';
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'component_id' => 'Content ID',
            'type'         => 'Type',
            'language'     => 'Language',
            'order'        => 'Order',
            'required'     => 'Required',
            'repeatable'   => 'Repeatable',
            'is_child'     => 'Is Child',
            'text'         => 'Text',
            'created_at'   => 'Created At',
            'updated_at'   => 'Updated At',
        ];
    }


    public function canBeDeleted()
    {
        return $this->repeatable == 1 && $this->is_child == 1;
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComponent()
    {
        return $this->hasOne(ContentComponent::className(), [ 'id' => 'component_id' ]);
    }


    public function hasChildren()
    {
        if ($this->isChildren())
        {
            return false;
        }
        $query = self::find()
                     ->where([
                         'component_id' => $this->component_id,
                         'type'         => $this->type,
                         'language'     => $this->language,
                     ])
                     ->andWhere([ 'is_child' => 1 ]);
        $child = $query->count();
        if (intval($child) > 0)
        {
            return true;
        }

        return false;
    }


    public function isChildren()
    {
        return boolval($this->is_child);
    }


    public function isLastChildren()
    {
        if (!$this->isChildren())
        {
            return false;
        }
        $child = self::find()
                     ->where([
                         'component_id' => $this->component_id,
                         'type'         => $this->type,
                         'language'     => $this->language,
                     ])
                     ->andWhere('`order` > :order', [ ':order' => $this->order ])
                     ->count();
        if (intval($child) > 0)
        {
            return false;
        }

        return true;
    }


    public function isRepeatable()
    {
        return boolval($this->repeatable);
    }


    public function isRequired()
    {
        return boolval($this->required);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'component_id', 'language', 'order', 'type' ], 'required' ],
            [ [ 'component_id', 'order', 'required', 'repeatable', 'is_child', 'created_at', 'updated_at' ], 'integer' ],
            [ [ 'text', 'type' ], 'string' ],
            [ [ 'language' ], 'string', 'max' => 16 ],
        ];
    }


    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }


    public function setRepeatable($value)
    {
        if ($value)
        {
            $this->repeatable = 1;
        }
        else
        {
            $this->repeatable = 0;
        }

        return $this->save(false);
    }
}
