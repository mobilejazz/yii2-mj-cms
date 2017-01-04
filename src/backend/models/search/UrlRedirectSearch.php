<?php

namespace mobilejazz\yii2\cms\backend\models\search;

use mobilejazz\yii2\cms\common\models\UrlRedirect;
use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UrlRedirectSearch represents the model behind the search form about `common\models\UrlRedirect`.
 */
class UrlRedirectSearch extends UrlRedirect
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'id' ], 'integer' ],
            [ [ 'origin_slug', 'destination_slug' ], 'safe' ],
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
        $query = UrlRedirect::find();

        $pageSize = isset($params[ 'per-page' ]) ? intval($params[ 'per-page' ]) : 20;

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [ 'pageSize' => $pageSize, ],
        ]);

        $this->load($params);

        if (!$this->validate())
        {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id'         => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere([ 'like', 'origin_slug', $this->origin_slug ])
              ->andFilterWhere([
                  'like',
                  'destination_slug',
                  $this->destination_slug,
              ]);

        return $dataProvider;
    }
}