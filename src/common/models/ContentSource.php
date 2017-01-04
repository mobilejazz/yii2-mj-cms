<?php

namespace mobilejazz\yii2\cms\common\models;

use yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the base-model class for table "content_source".
 *
 * @property integer            $id
 * @property string             $view
 * @property integer            $author_id
 * @property integer            $updater_id
 * @property integer            $status
 * @property integer            $is_homepage
 * @property integer            $published_at
 * @property integer            $created_at
 * @property integer            $updated_at
 * @property ContentComponent[] $contentComponents
 * @property ContentSlug[]      $contentSlugs
 * @property ContentMetaTag[]   $contentMetaTags
 * @property ComponentField[]   $componentFields
 * @property User               $updater
 * @property User               $author
 */
class ContentSource extends ActiveRecord
{

    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_DELETED = 2;
    const STATUS_PRIVATE_CONTENT = 3;

    public $publish_date_string;

    /** @var ContentMetaTag */
    public $meta_tags;


    /**
     * @return array map of contents grouped by views and then ids and status of each one of them.
     */
    public static function getAsContentMap()
    {
        $all = ContentSource::find()
                            ->where([ 'NOT LIKE', 'status', self::STATUS_DELETED ])
                            ->all();

        return ArrayHelper::map($all, "id", "status", "view");
    }


    public static function getAsDropDownData()
    {
        $result = [];
        /** @var ContentSource[] $array */
        $array = ContentSource::find()
                              ->all();
        foreach ($array as $element)
        {
            $key                                                  = $element->id;
            $value                                                = $element->getTitle();
            $result[ Views::getViewName($element->view) ][ $key ] = $value;
        }

        return $result;
    }


    public function getTitle()
    {
        /** @var ContentSlug $cs */
        $cs = $this->hasOne(ContentSlug::className(), [ 'content_id' => 'id', ])
                   ->andWhere([ 'language' => Yii::$app->language ])
                   ->orderBy([ 'updated_at' => SORT_DESC ])
                   ->one();

        return $cs->title;
    }


    public static function getRecentlyChanged()
    {
        return ContentSource::find()
                            ->limit(10)
                            ->orderBy([ 'updated_at' => SORT_DESC ])
                            ->all();
    }


    /**
     * Return the key and the name of all the views as a map.
     * @return array[] of views
     */
    public static function statusAsMap()
    {
        $array_to_return = [];

        $views = self::status();
        foreach ($views as $key => $value)
        {
            if ($key == self::STATUS_DELETED)
            {
                continue;
            }
            $array_to_return[ $key ] = $value;
        }

        return $array_to_return;
    }


    /**
     * @return array of targets
     */
    public static function status()
    {
        return [
            ContentSource::STATUS_DRAFT           => Yii::t('backend', 'Draft'),
            ContentSource::STATUS_PUBLISHED       => Yii::t('backend', 'Published'),
            ContentSource::STATUS_DELETED         => Yii::t('backend', 'Deleted'),
            ContentSource::STATUS_PRIVATE_CONTENT => \Yii::t('backend', 'Private Content')
        ];
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'content_source';
    }


    public function isHomePage()
    {
        return boolval($this->is_homepage);
    }


    public function setAsHomePage()
    {
        /** @var ContentSource[[] $hp */
        $hp = self::find()
                  ->where([ 'is_homepage' => 1 ])
                  ->all();

        foreach ($hp as $h)
        {
            /** @var ContentSource $h */
            $h->is_homepage = 0;
            $h->save();
        }

        $this->is_homepage = 1;
        $this->save();
    }


    /**
     * @param bool  $insert
     * @param array $changedAttributes
     *
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // Load the details as well.
        if (\Yii::$app->request->isPost)
        {
            if (!isset($_POST[ 'Locale' ]))
            {
                $data        = $_POST;
                $transaction = \Yii::$app->db->beginTransaction();

                try
                {
                    // DELETE LANGUAGES AND INSERT THEM AGAIN.
                    ContentMetaTag::deleteAll([ 'content_id' => $this->id, 'language' => \Yii::$app->language ]);

                    // Ensure there is at least a default description meta tag
                    $data = ContentMetaTag::ensureDefault($data, Views::getViewDescription($this->view));

                    foreach ($data[ 'ContentSource' ][ 'meta_tags' ] as $tag)
                    {
                        $ml             = new ContentMetaTag();
                        $ml->content_id = $this->id;
                        $ml->language   = \Yii::$app->language;
                        $ml->name       = $tag[ 'name' ];
                        $ml->content    = $tag[ 'content' ];
                        $ml->save();
                    }
                    $transaction->commit();
                }
                catch (\Exception $e)
                {
                    $transaction->rollBack();
                    throw $e;
                }
            }

        }

        $this->reloadFields();
    }


    private function reloadFields()
    {
        $this->meta_tags = $this->getCurrentMetaTags();
    }


    /**
     * @return array|ContentMetaTag[]
     */
    public function getCurrentMetaTags()
    {
        return $this->getMetaTags()
                    ->where([ 'language' => \Yii::$app->language ])
                    ->all();
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMetaTags()
    {
        return $this->hasMany(ContentMetaTag::className(), [ 'content_id' => 'id' ]);
    }


    public function afterFind()
    {
        parent::afterFind();

        $this->publish_date_string = is_null($this->published_at) ? \Yii::$app->formatter->asDate(strtotime('+1 month', time()),
            'dd-M-yyyy') : Yii::$app->formatter->asDate($this->published_at, "dd-M-yyyy");

        $this->reloadFields();
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                  => Yii::t('app', 'ID'),
            'title'               => Yii::t('app', 'Title'),
            'body'                => Yii::t('app', 'Body'),
            'view'                => Yii::t('app', 'View type'),
            'thumbnail_base_url'  => Yii::t('app', 'Thumbnail Base Url'),
            'thumbnail_path'      => Yii::t('app', 'Thumbnail Path'),
            'author_id'           => Yii::t('app', 'Author'),
            'updater_id'          => Yii::t('app', 'Updater ID'),
            'status'              => Yii::t('app', 'Status'),
            'published_at'        => Yii::t('app', 'Publish Date'),
            'publish_date_string' => Yii::t('backend', 'Publish Date'),
            'created_at'          => Yii::t('app', 'Creation Date'),
            'updated_at'          => Yii::t('app', 'Updated At'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), [ 'id' => 'author_id' ]);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContentSlugs()
    {
        return $this->hasMany(ContentSlug::className(), [ 'content_id' => 'id' ]);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContentMetaTags()
    {
        return $this->hasMany(ContentMetaTag::className(), [ 'content_id' => 'id', ]);
    }


    public function getCurrentContentSlug($lang)
    {
        return $this->getCurrentSlug($lang)->slug;
    }


    /**
     * @param $lang
     *
     * @return ActiveRecord|ContentSlug
     */
    public function getCurrentSlug($lang)
    {
        $locale = Locale::findByIdentifier($lang);

        return ContentSlug::find()
                          ->where([ 'content_id' => $this->id, 'language' => Locale::getIdentifier($locale) ])
                          ->orderBy([ 'updated_at' => SORT_DESC ])
                          ->one();
    }


    /**
     * Returns the content components ordered in a map <GROUP_ID><ARRAY_OF_COMPONENTS> so we can retrieve everything in a single call.
     *
     * @param $lang
     *
     * @return array
     */
    public function getOrderedContentComponentsByGroup($lang)
    {
        /** @var ContentComponent[] $contents */
        $contents = $this->getOrderedContentComponents($lang);

        $map = [];

        foreach ($contents as $cnt)
        {
            if (!$cnt->isChildren())
            {
                $map[ $cnt->id ]   = [];
                $map[ $cnt->id ][] = $cnt;
            }
            else
            {
                $map[ $cnt->group_id ][ 'children' ][] = $cnt;
            }
        }

        return $map;
    }


    /**
     * @param $lang
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getOrderedContentComponents($lang)
    {
        return $this->getContentComponents()
                    ->andWhere([ 'language' => $lang ])
                    ->orderBy([ 'order' => SORT_ASC, 'is_child' => SORT_ASC ])
                    ->all();
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContentComponents()
    {
        return $this->hasMany(ContentComponent::className(), [ 'content_id' => 'id' ]);
    }


    /**
     * @param $lang
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getReverseOrderedContentComponents($lang)
    {
        return $this->getContentComponents()
                    ->andWhere([ 'language' => $lang ])
                    ->orderBy([ 'order' => SORT_DESC, 'is_child' => SORT_DESC, ])
                    ->all();
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdater()
    {
        return $this->hasOne(User::className(), [ 'id' => 'updater_id' ]);
    }


    /**
     * Publishes a content if not published or un-publishes a content if published.
     *
     * @param bool|true $publish
     */
    public function publish($publish = true)
    {
        if ($publish && !$this->status)
        {
            $this->status = self::STATUS_PUBLISHED;
            $this->save();
        }
        elseif (!$publish && $this->status)
        {
            $this->status = self::STATUS_DRAFT;
            $this->save();
        }
    }


    /**
     * Override the delete functionality adding simply a new status (deleted).
     * @return false|int|void
     * @throws \Exception
     */
    public function delete()
    {
        // Check if this is being used in a Menu
        $menu_item = MenuItem::findOne([ 'content_id' => $this->id ]);
        if (isset($menu_item) && !is_null($menu_item))
        {
            \Yii::$app->session->setFlash('error',
                \Yii::t('backend', 'You can not remove a content that is used inside a Menu. Please remove the content from the menu first.'));

            return;
        }

        // IF THIS CONTENT IS ALREADY IN STATUS DELETED,
        // FULLY DELETE THE CONTENT FROM THE DATABASE.
        if ($this->status == self::STATUS_DELETED)
        {
            parent::delete();

            return;
        }

        // ELSE JUST SET A LABEL OF DELETED
        $this->status = self::STATUS_DELETED;
        $this->save();
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'view' ], 'required' ],
            [
                [
                    'author_id',
                    'updater_id',
                    'status',
                    'created_at',
                    'updated_at',
                    'published_at',
                ],
                'integer'
            ],
            [ [ 'view' ], 'string' ],
        ];
    }


    public function getStatus()
    {
        return self::status()[ $this->status ];
    }
}
