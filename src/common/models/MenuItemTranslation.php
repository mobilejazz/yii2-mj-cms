<?php

namespace mobilejazz\yii2\cms\common\models;

use mobilejazz\yii2\cms\common\components\TimeStampActiveRecord;

/**
 * This is the base-model class for table "menu_translations".
 *
 * @property integer  $id
 * @property integer  $menu_item_id
 * @property string   $language
 * @property string   $title
 * @property string   $link
 * @property integer  $created_at
 * @property integer  $updated_at
 *
 * @property MenuItem $menu
 */
class MenuItemTranslation extends TimeStampActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_item_translation';
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'menu_item_id' => 'Menu ID',
            'language'     => 'Language',
            'title'        => 'Title',
            'link'         => 'Link',
            'created_at'   => 'Created At',
            'updated_at'   => 'Updated At',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(MenuItem::className(), [ 'id' => 'menu_item_id' ]);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'menu_item_id', 'language', 'title' ], 'required' ],
            [ [ 'menu_item_id', 'created_at', 'updated_at' ], 'integer' ],
            [ [ 'language' ], 'string', 'max' => 16 ],
            [ [ 'title' ], 'string', 'max' => 45 ],
            [ [ 'link' ], 'string', 'max' => 255 ],
        ];
    }

}
