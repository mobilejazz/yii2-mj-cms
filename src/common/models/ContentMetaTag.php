<?php

namespace mobilejazz\yii2\cms\common\models;

use mobilejazz\yii2\cms\common\components\TimeStampActiveRecord;
use yii;

/**
 * This is the base-model class for table "content_meta_tag".
 *
 * @property integer $id
 * @property integer $content_id
 * @property string  $language
 * @property string  $name
 * @property string  $content
 * @property integer $created_at
 * @property integer $updated_at
 * @property string  $aliasModel
 */
class ContentMetaTag extends TimeStampActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'content_meta_tag';
    }


    /**
     * Ensure ContentMetaTag description is present and if not creates it with provided default.
     * If Default is not provided, the contentSource title is used.
     * Expected data entry is the POST from contentSource update view
     *
     * @param array  $data
     * @param string $default
     *
     * @return array
     */
    public static function ensureDefault($data, $default)
    {
        //if default is empty or not set, use title
        if (!isset($default) || ($default == ''))
        {
            /*
            TODO search for a text component and use that as default
            if(isset($data[ 'ContentSlug' ][ 'title' ])){
                $default = $data[ 'ContentSlug' ][ 'title' ];
            }else{*/
            $default = '';
            //}

        }

        //ensure meta_tags array is there
        if (!isset($data[ 'ContentSource' ][ 'meta_tags' ]) || !is_array($data[ 'ContentSource' ][ 'meta_tags' ]))
        {
            $data[ 'ContentSource' ][ 'meta_tags' ] = [];
        }

        //find description metatag and update if empty
        $found = false;
        foreach ($data[ 'ContentSource' ][ 'meta_tags' ] as $tag)
        {
            if (isset($tag[ 'name' ]) && ($tag[ 'name' ] == 'description'))
            {
                $found = true;
                if (!isset($tag[ 'content' ]) || trim($tag[ 'content' ]) == '')
                {
                    $tag[ 'content' ] = $default;
                }
            }
        }

        //add description metatag if not there
        if (!$found)
        {
            $data[ 'ContentSource' ][ 'meta_tags' ][] = [
                'name'    => 'description',
                'content' => $default
            ];
        }

        return $data;
    }


    /**
     * Alias name of table for crud viewsLists all Area models.
     * Change the alias name manual if needed later
     *
     * @param bool $plural
     *
     * @return string
     */
    public function getAliasModel($plural = false)
    {
        if ($plural)
        {
            return Yii::t('backend', 'ContentMetaTags');
        }
        else
        {
            return Yii::t('backend', 'ContentMetaTag');
        }
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'content_id', 'language', 'name', 'content' ], 'required' ],
            [ [ 'content_id', 'created_at', 'updated_at' ], 'integer' ],
            [ [ 'content' ], 'string' ],
            [ [ 'language' ], 'string', 'max' => 16 ],
            [ [ 'name' ], 'string', 'max' => 512 ]
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => \Yii::t('backend', 'ID'),
            'content_id' => \Yii::t('backend', 'Content ID'),
            'language'   => \Yii::t('backend', 'Language'),
            'name'       => \Yii::t('backend', 'Meta Name'),
            'content'    => \Yii::t('backend', 'Meta Content'),
            'created_at' => \Yii::t('backend', 'Created At'),
            'updated_at' => \Yii::t('backend', 'Updated At'),
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return array_merge(parent::attributeHints(), [
            'id'         => \Yii::t('backend', 'ID'),
            'content_id' => \Yii::t('backend', 'Content Id'),
            'language'   => \Yii::t('backend', 'Language'),
            'name'       => \Yii::t('backend', 'Meta Name'),
            'content'    => \Yii::t('backend', 'Meta Content'),
            'created_at' => \Yii::t('backend', 'Created At'),
            'updated_at' => \Yii::t('backend', 'Updated At'),
        ]);
    }

}
