<?php

namespace mobilejazz\yii2\cms\backend\models\search;

use mobilejazz\yii2\cms\common\models\ContentSlug;
use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ContentSlugSearch represents the model behind the search form about `common\models\ContentSlug`.
 */
class ContentSlugSearch extends ContentSlug
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'id',
                    'content_id',
                    'system',
                    'created_at',
                    'updated_at'
                ],
                'integer'
            ],
            [
                [
                    'language',
                    'slug',
                    'title'
                ],
                'safe'
            ],
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
        $query = ContentSlug::find();

        $pageSize = isset($params[ 'per-page' ]) ? intval($params[ 'per-page' ]) : 20;

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [ 'pageSize' => $pageSize, ],
        ]);

        $this->load($params);

        if (!$this->validate())
        {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id'         => $this->id,
            'content_id' => $this->content_id,
            'system'     => $this->system,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere([ 'like', 'language', $this->language ])
              ->andFilterWhere([ 'like', 'slug', $this->slug ])
              ->andFilterWhere([ 'like', 'title', $this->title ]);

        return $dataProvider;
    }
}