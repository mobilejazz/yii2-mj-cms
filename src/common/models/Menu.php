<?php

namespace mobilejazz\yii2\cms\common\models;

use mobilejazz\yii2\cms\common\components\TimeStampActiveRecord;

/**
 * This is the base-model class for table "menu".
 *
 * @property integer    $id
 * @property string     $key
 * @property string     $class
 * @property integer    $created_at
 * @property integer    $updated_at
 *
 * @property MenuItem[] $menuItems
 */
class Menu extends TimeStampActiveRecord
{

    public static function getAllCssClass()
    {
        $tr  = [];
        $all = Menu::find()
                   ->all();
        /** @var Menu $t */
        foreach ($all as $t)
        {
            $tr[ $t->id ] = $t->class;
        }

        return $tr;
    }


    public static function getAllKeys()
    {
        $tr  = [];
        $all = Menu::find()
                   ->all();
        /** @var Menu $t */
        foreach ($all as $t)
        {
            $tr[ $t->id ] = $t->key;
        }

        return $tr;
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }


    /**
     * @return array|MenuItem[]
     */
    public function getSortedMenuItems()
    {
        return Menu::menuBuilder($this->getMenuItems()
                                      ->orderBy([ 'order' => SORT_ASC ])
                                      ->all());
    }


    /**
     * @param     array MenuItem[] $menu_items
     * @param int $parent
     *
     * @return array
     */
    public static function menuBuilder(array $menu_items, $parent = 0)
    {
        $branch = [];

        foreach ($menu_items as $item)
        {
            if ($item[ 'parent' ] == $parent)
            {
                $children = Menu::menuBuilder($menu_items, $item[ 'id' ]);
                if ($children)
                {
                    $item[ 'children' ] = $children;
                    $item[ 'childs' ]   = 'true';
                }
                $branch[] = $item;
            }
        }

        return $branch;
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItems()
    {
        return $this->hasMany(MenuItem::className(), [ 'menu_id' => 'id' ]);
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'key'        => 'Key',
            'class'      => 'Class',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'key' ], 'required' ],
            [ [ 'created_at', 'updated_at' ], 'integer' ],
            [ [ 'key', 'class' ], 'string', 'max' => 255 ],
        ];
    }

}
