<?php

namespace mobilejazz\yii2\cms\common\models;

use mobilejazz\yii2\cms\common\components\TimeStampActiveRecord;
use mobilejazz\yii2\cms\common\validators\ParentMenuValidator;
use yii;
use yii\db\ActiveRecord;

/**
 * This is the base-model class for table "menu_item".
 *
 * @property integer               $id
 * @property integer               $menu_id
 * @property integer               $parent
 * @property integer               $order
 * @property integer               $target
 * @property string                $class
 * @property integer               $content_id
 * @property integer               $created_at
 * @property integer               $updated_at
 *
 * @property Menu                  $menu
 * @property MenuItemTranslation[] $menuItemTranslations
 */
class MenuItem extends TimeStampActiveRecord
{

    const TARGET_CURRENT = 0;
    const TARGET_BLANK = 1;

    /** @var  array MenuItem[] */
    public $children;

    public $childs;


    public static function getAllTitles()
    {
        $tr  = [
            0 => 'TOP LEVEL MENU',
        ];
        $all = MenuItem::find()
                       ->all();
        /** @var MenuItem $t */
        foreach ($all as $t)
        {
            $tr[ $t->id ] = $t->getCurrentTitle();
        }

        return $tr;
    }


    public function getCurrentTitle()
    {
        return $this->getCurrentTranslation(Yii::$app->language)->title;
    }


    /**
     * @param $lang
     *
     * @return array|MenuItemTranslation|ActiveRecord
     */
    public function getCurrentTranslation($lang)
    {
        return $this->hasOne(MenuItemTranslation::className(), [ 'menu_item_id' => 'id', ])
                    ->andWhere([ 'language' => $lang ])
                    ->orderBy([ 'updated_at' => SORT_DESC ])
                    ->one();
    }


    /**
     * @param $id
     *
     * @return MenuItem|static
     */
    public static function getParent($id)
    {
        return MenuItem::findOne([ $id => 'parent' ]);
    }


    /**
     * @return array of targets
     */
    public static function targets()
    {
        return [
            MenuItem::TARGET_CURRENT => "Current page (Default)",
            MenuItem::TARGET_BLANK   => "New page (_blank)",
        ];
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_item';
    }


    static function sanitizeOrder($menuId, $parentId)
    {

        $menuItems = MenuItem::find()
                             ->where([ 'menu_id' => $menuId, 'parent' => $parentId ])
                             ->orderBy([ 'order' => 'ASC' ])
                             ->all();

        $prevOrder  = -1;
        $dirtyItems = [];

        /** @var MenuItem $menuItem */
        foreach ($menuItems as $menuItem)
        {
            if ($prevOrder == -1)
            {
                $prevOrder = $menuItem->order;
            }
            else
            {

                if ($menuItem->order != $prevOrder + 1)
                {
                    $menuItem->order = $prevOrder + 1;
                    $dirtyItems[]    = $menuItem;
                }

                $prevOrder = $menuItem->order;
            }
        }

        foreach ($dirtyItems as $menuItem)
        {
            $menuItem->save();
        }

    }


    public function getParentTitle()
    {
        if ($this->parent == 0)
        {
            return null;
        }
        else
        {
            return MenuItem::findOne($this->parent)
                           ->getCurrentTitle();
        }
    }


    /**
     * @var $t MenuItem
     * @return array
     */
    public function getPossibleParents()
    {
        $tr = [ 0 => "None", ];

        // Get all menu items sorted in a multidimensional array.
        /** @var MenuItem $menu_items */
        $array = self::getChildrenFor(MenuItem::findAll([ 'menu_id' => $this->menu_id ]), $this->id);

        // FOREACH IN WHICH WE WILL JUST NOT ADD ALL OF THE CHILDREN OF THIS ARRAY.
        foreach ($array as $menu_item)
        {
            if (!isset($menu_item) || $menu_item == $this->id)
            {
                continue;
            }
            else
            {
                $item            = MenuItem::findOne($menu_item);
                $tr[ $item->id ] = $item->getCurrentTitle();
            }
        }

        return $tr;
    }


    static function getChildrenFor($ary, $id)
    {
        $results = [];

        foreach ($ary as $el)
        {
            if ($el->parent == $id && $el->parent != 0)
            {
                $copy = $el;
                unset($copy[ 'children' ]); // remove child elements
                $results[] = $copy;
            }
            if (count($el[ 'children' ]) > 0 && ($children = MenuItem::getChildrenFor($el[ 'children' ], $id)) !== false)
            {
                $results = array_merge($results, $children);
            }
        }

        $diff = array_map(function ($i)
        {
            return $i;
        }, array_diff(array_map(function ($i)
        {
            return $i[ 'id' ];
        }, $ary), array_map(function ($i)
        {
            return $i[ 'id' ];
        }, $results)));

        return $diff;
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'parent', 'order', 'target', 'created_at', 'updated_at', 'content_id' ], 'integer' ],
            // We only need to check if the parent is VALID on update.
            [ [ 'parent' ], ParentMenuValidator::className(), 'on' => 'update' ],
            [ [ 'class' ], 'string', 'max' => 255 ],
            [ [ 'order', 'parent', 'content_id' ], 'default', 'value' => 0 ],
        ];
    }


    /**
     * Add an update scenario so we can validate that the parents
     * placed on "update" are correct.
     * @return array
     */
    public function scenarios()
    {
        $scenarios             = parent::scenarios();
        $scenarios[ 'update' ] = [ 'parent', 'order', 'target', 'content_id', 'class', 'updated_at' ];

        return $scenarios;
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'parent'     => 'Parent',
            'order'      => 'Order',
            'target'     => 'Target',
            'class'      => 'CSS Class',
            'content_id' => 'Content ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItemTranslations()
    {
        return $this->hasMany(MenuItemTranslation::className(), [ 'menu_item_id' => 'id' ]);
    }

}
