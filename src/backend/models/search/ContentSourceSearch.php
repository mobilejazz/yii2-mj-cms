<?php

namespace mobilejazz\yii2\cms\backend\models\search;

use mobilejazz\yii2\cms\common\models\ContentSource;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ContentSourceSearch represents the model behind the search form about `common\models\ContentSource`.
 */
class ContentSourceSearch extends ContentSource
{

    public $title;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'id',
                    'author_id',
                    'updater_id'
                ],
                'integer'
            ],
            [
                [
                    'view',
                    'status'
                ],
                'string'
            ],
            [ [ 'title' ], 'safe' ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }


    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ContentSource::find();
        $query->joinWith([ 'contentSlugs' ]);
        $lang = \Yii::$app->language;
        $query->andWhere([ 'content_slug.language' => $lang ]);
        $query->andWhere([ '<>', 'status', self::STATUS_DELETED ]);
        $query->groupBy('content_source.id');

        $pageSize = isset($params[ 'per-page' ]) ? intval($params[ 'per-page' ]) : 20;

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [ 'pageSize' => $pageSize, ],
        ]);

        $dataProvider->sort->attributes[ 'title' ] = [
            'asc'  => [ 'content_slug.title' => SORT_ASC ],
            'desc' => [ 'content_slug.title' => SORT_DESC ],
        ];

        $dataProvider->sort->attributes[ 'content_slug.slug' ] = [
            'asc'  => [ 'content_slug.slug' => SORT_ASC ],
            'desc' => [ 'content_slug.slug' => SORT_DESC ],
        ];

        if (!($this->load($params) && $this->validate()))
        {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id'           => $this->id,
            'view'         => $this->view,
            'author_id'    => $this->author_id,
            'updater_id'   => $this->updater_id,
            'status'       => $this->status,
            'published_at' => $this->published_at,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
        ]);

        $query->andFilterWhere([
            'like',
            'title',
            $this->title,
        ]);

        return $dataProvider;
    }
}