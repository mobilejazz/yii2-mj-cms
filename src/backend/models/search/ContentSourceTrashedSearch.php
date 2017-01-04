<?php

namespace mobilejazz\yii2\cms\backend\models\search;

use mobilejazz\yii2\cms\common\models\ContentSource;
use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ContentSourceSearch represents the model behind the search form about `common\models\ContentSource`.
 */
class ContentSourceTrashedSearch extends ContentSource
{

    public $title;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'id', 'author_id', 'updater_id' ], 'integer' ],
            [ [ 'view', 'status' ], 'string' ],
            [ [ 'title' ], 'safe' ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
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

        $query->andWhere([ 'status' => ContentSource::STATUS_DELETED, ]);
        $query->andWhere([ 'status' => ContentSource::STATUS_DELETED, ]);

        $pageSize = isset($params[ 'per-page' ]) ? intval($params[ 'per-page' ]) : 20;

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [ 'pageSize' => $pageSize, ],
        ]);

        $dataProvider->sort->attributes[ 'title' ] = [
            'asc'  => [ 'content_slug.title' => SORT_ASC ],
            'desc' => [ 'content_slug.title' => SORT_DESC ],
        ];

        $dataProvider->sort->attributes[ 'slug' ] = [
            'asc'  => [ 'content_slug.slug' => SORT_ASC ],
            'desc' => [ 'content_slug.slug' => SORT_DESC ],
        ];

        $this->load($params);

        return $dataProvider;
    }
}