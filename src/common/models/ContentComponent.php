<?php

namespace mobilejazz\yii2\cms\common\models;

use yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 *
 * Classes defined:
 * 1. GridComponent.
 *
 * Every Field has an ID defined and it HAS TO BE unique. Also, please, don't change the IDS
 * without explicitly changing them in the database as well.
 *
 * @property integer          $id
 * @property integer          $content_id
 * @property string           $type
 * @property string           $language
 * @property string           $title
 * @property integer          $order
 * @property integer          $repeatable
 * @property integer          $group_id
 * @property integer          $is_child
 * @property integer          $created_at
 * @property integer          $updated_at
 *
 * @property ComponentField[] $componentFields
 * @property ContentSource    $content
 *
 */
class ContentComponent extends ActiveRecord
{

    public static function tableName()
    {
        return 'content_component';
    }


    /**
     * Creates a new Component and returns its order.
     *
     * @param integer       $type
     * @param ContentSource $owner
     * @param string        $locale
     * @param int           $order
     * @param array         $options
     * @param null          $parent_component
     * @param bool          $look_for_inner
     *
     * @return ContentComponent.
     */
    public static function create($type, $owner, $locale, $order, $options = null, $parent_component = null, $look_for_inner = true)
    {
        $parent = null;

        // If parent component is different than null, it means that this
        // component will be part of a group, therefore we need to
        // set it right bellow that parent.
        if ($parent_component != null)
        {
            /** @var ContentComponent $parent */
            $parent = ContentComponent::findOne($parent_component);
        }

        // Create a new component following the instructions
        // of the parameters and the configuration file.
        $title       = !$look_for_inner ? Components::getName($owner->view, $type) : Components::getName(null, $type);
        $child_order = 0;

        // Child specific values.
        if (isset($parent) && $parent != null)
        {
            $parent_group = self::getGroup($parent);
            $child_order  = count($parent_group);
            $order        = $parent->order;
        }

        // Define new ContentComponent.
        $component             = new ContentComponent();
        $component->content_id = $owner->id;
        $component->type       = $type;
        $component->language   = $locale;
        $component->title      = $title;
        $component->order      = $order;
        if (isset($options) && isset($options[ 'repeatable' ]))
        {
            $component->repeatable = intval($options[ 'repeatable' ]);
        }
        else
        {
            $component->repeatable = Components::isRepeatable($owner->view, $type);
        }
        $component->is_child = $child_order;

        // Save the component to we can later retrieve an ID.
        $component->save(false);

        // Group ID is saved here because we now have an ID.
        if ($parent == null && !Components::isGroupable($component->content->view, $type))
        {
            $component->group_id = 0;
        }
        else if ($parent != null && $parent->group_id != 0)
        {
            $component->group_id = $parent->group_id;
        }
        else if (Components::isGroupable($component->content->view, $type))
        {
            $component->group_id = $component->id;
        }
        else
        {
            $component->group_id = $parent->id;
        }

        $component->save(false);

        // ADD FIELDS.
        $fields      = Components::getFields($type);
        $field_order = 0;
        foreach ($fields as $type => $value)
        {
            ComponentField::create($component, $type, $value, false, $field_order++, Fields::getDefaultValue($component->type, $type));
        }

        $inner_components = Components::getInnerComponents($component->type);
        if ($look_for_inner && isset($inner_components) && $inner_components != null)
        {
            $component->group_id = $component->id;
            $component->save(false);
            foreach ($inner_components as $type => $options)
            {
                ContentComponent::create($type, $component->content, $locale, null, $options, $component->id, false);
            }
        }

        return $component;
    }


    /**
     * @param ContentComponent $component
     *
     * @return ContentComponent[] array|ContentComponent|
     */
    public static function getGroup($component)
    {
        if ($component->group_id == 0)
        {
            return [ $component ];
        }

        return self::find()
                   ->where([
                       'content_id' => $component->content_id,
                       'language'   => $component->language,
                       'group_id'   => $component->group_id,
                   ])
                   ->orderBy([ 'order' => SORT_ASC, 'is_child' => SORT_ASC, ])
                   ->all();
    }


    /**
     * Move UP a given component.
     *
     * @param ContentComponent $component
     */
    public static function moveGroupUp($component)
    {
        // If already on top, return.
        if ($component->order == 0)
        {
            return;
        }

        /** @var ContentComponent[] $group_to_go_down */
        $group_to_go_down = self::getPreviousGroup($component);
        // If there is no group to move down, return.
        if ($group_to_go_down == null)
        {
            return;
        }

        /** @var ContentComponent[] $group_to_go_up */
        $group_to_go_up = self::getGroup($component);

        foreach ($group_to_go_down as $cmp)
        {
            $cmp->order = $cmp->order + 1;
            $cmp->save(false);
        }

        foreach ($group_to_go_up as $cmp)
        {
            $cmp->order = $cmp->order - 1;
            $cmp->save(false);
        }
    }


    /**
     * Returns the previous group, if any.
     *
     * @param ContentComponent $component
     *
     * @return array|null|\yii\db\ActiveRecord[]
     */
    public static function getPreviousGroup($component)
    {
        if ($component->order == 0)
        {
            return null;
        }

        return self::find()
                   ->where([
                       'content_id' => $component->content_id,
                       'language'   => $component->language,
                       'order'      => $component->order - 1,
                   ])
                   ->orderBy([ 'order' => SORT_ASC, 'is_child' => SORT_ASC, ])
                   ->all();
    }


    public static function setGroupOrder($component, $order)
    {
        $group = self::getGroup($component);

        foreach ($group as $cmp)
        {
            $cmp->order = $order;
            $cmp->save(false);
        }
    }


    /**
     * Move DOWN a given component.
     *
     * @param ContentComponent $component
     */
    public static function moveGroupDown($component)
    {

        /** @var ContentComponent[] $group_to_go_down */
        $group_to_go_up = self::getNextGroup($component);

        // If there is no next group, it means that we already are in the last group.
        if ($group_to_go_up == null)
        {
            return;
        }

        /** @var ContentComponent[] $group_to_go_up */
        $group_to_go_down = self::getGroup($component);

        foreach ($group_to_go_down as $cmp)
        {
            $cmp->order = $cmp->order + 1;
            $cmp->save(false);
        }

        foreach ($group_to_go_up as $cmp)
        {
            $cmp->order = $cmp->order - 1;
            $cmp->save(false);
        }
    }


    /**
     * Returns the next group, if any.
     *
     * @param ContentComponent $component
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getNextGroup($component)
    {
        // GET MAX ORDER
        $max = self::getMaxOrder($component);

        if ($component->order == $max)
        {
            return null;
        }

        return self::find()
                   ->where([
                       'content_id' => $component->content_id,
                       'language'   => $component->language,
                       'order'      => $component->order + 1,
                   ])
                   ->orderBy([ 'order' => SORT_ASC, 'is_child' => SORT_ASC, ])
                   ->all();
    }


    public static function getMaxOrder($component)
    {
        $order = self::find()
                     ->where([
                         'content_id' => $component->content_id,
                         'language'   => $component->language,
                     ])
                     ->max('`order`');

        if ($order > 0)
        {
            return $order;
        }

        return 0;
    }


    /**
     * @param ContentComponent $component
     */
    public static function moveWithinGroupUp($component)
    {
        // If already a parent, do nothing.
        if (!$component->isChildren())
        {
            return;
        }

        // Get the group.
        $group = self::getGroup($component);

        // For now just swap the values.
        $other = $group[ $component->is_child - 1 ];
        // Is the target a parent or a children?
        $other_is_children = $other->isChildren();

        $component->is_child = $component->is_child - 1;
        $other->is_child     = $other->is_child + 1;
        $component->save(false);
        $other->save(false);

        // If the other is the parent, we need to update pretty much everything.
        if (!$other_is_children)
        {
            foreach ($group as $cmp)
            {
                $cmp->group_id = $component->id;
                $cmp->save(false);
            }
        }
    }


    public function isChildren()
    {
        return $this->is_child != 0;
    }


    /**
     * @param ContentComponent $component
     */
    public static function moveWithinGroupDown($component)
    {
        $group     = self::getGroup($component);
        $max_order = count($group) - 1;

        // If this is the last member, return.
        if ($component->is_child == $max_order)
        {
            return;
        }

        $children = $component->isChildren();

        // For now just swap the values.
        $other               = $group[ $component->is_child + 1 ];
        $component->is_child = $component->is_child + 1;
        $other->is_child     = $other->is_child - 1;
        $component->save(false);
        $other->save(false);

        // This is the parent, so we need to update pretty much everything.
        if (!$children)
        {
            foreach ($group as $cmp)
            {
                $cmp->group_id = $other->id;
                $cmp->save(false);
            }
        }
    }


    /**
     * Reorders the components so no gaps ever happen.
     *
     * @param ContentSource $content
     */
    public static function sanitizeOrder($content)
    {
        /** @var ContentComponent[] $components */
        $components = $content->getOrderedContentComponents(Yii::$app->language);

        $order       = -1;
        $child_order = 0;
        foreach ($components as $c)
        {
            if (!$c->isChildren())
            {
                $order       = $order + 1;
                $child_order = 0;
            }
            else
            {
                $child_order = $child_order + 1;
            }
            $c->order    = $order;
            $c->is_child = $child_order;
            $c->save(false);
        }
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('backend', 'ID'),
            'content_id' => Yii::t('backend', 'Content ID'),
            'type'       => Yii::t('backend', 'Component Type'),
            'language'   => Yii::t('backend', 'Language'),
            'title'      => Yii::t('backend', 'Component Title'),
            'order'      => Yii::t('backend', 'Order'),
            'repeatable' => Yii::t('backend', 'Repeatable'),
            'is_child'   => Yii::t('backend', 'Is Child'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContent()
    {
        return $this->hasOne(ContentSource::className(), [ 'id' => 'content_id' ]);
    }


    public function getOrderedComponentFields($lang)
    {
        return $this->getComponentFields()
                    ->andWhere([ 'language' => $lang ])
                    ->orderBy([ 'order' => SORT_ASC ])
                    ->all();
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComponentFields()
    {
        return $this->hasMany(ComponentField::className(), [ 'component_id' => 'id' ])
                    ->orderBy([ 'order' => SORT_ASC ]);
    }


    public function isRepeatable()
    {
        return $this->repeatable == 1;
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'content_id', 'language', 'type' ], 'required' ],
            [ [ 'content_id', 'order', 'repeatable', 'is_child', 'group_id', 'created_at', 'updated_at' ], 'integer' ],
            [ [ 'title', 'type' ], 'string' ],
            [ [ 'language' ], 'string', 'max' => 16 ],
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


    public function isGroupable()
    {
        return $this->group_id != 0;
    }


    public function isFirstInGroup()
    {
        if (!$this->isChildren() && (count(self::getGroup($this)) - 1) > 0)
        {
            return true;
        }

        return false;
    }


    public function isLastInGroup()
    {
        if (!$this->isChildren())
        {
            return false;
        }

        $order = count(self::getGroup($this)) - 1;

        if ($this->is_child == $order)
        {
            return true;
        }

        return false;
    }


    public function beforeDelete()
    {
        $group = self::getGroup($this);

        $children = $this->isChildren();

        // For now just swap the values.
        $other = $group[ $this->is_child + 1 ];
        if (isset($other))
        {
            $other->is_child = $other->is_child - 1;
            $other->save(false);

            // This is the parent, so we need to update pretty much everything.
            if (!$children)
            {
                foreach ($group as $cmp)
                {
                    $cmp->group_id = $other->id;
                    $cmp->save(false);
                }
            }
        }

        return parent::beforeDelete();
    }


    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }


    public function displayTitle()
    {
        return Components::displayTitle($this->content->view, $this->type);
    }
}
