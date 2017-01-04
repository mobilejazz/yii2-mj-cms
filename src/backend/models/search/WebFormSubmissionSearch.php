<?php

namespace mobilejazz\yii2\cms\backend\models\search;

use mobilejazz\yii2\cms\common\models\WebFormSubmission;
use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * WebFormSubmissionSearch represents the model behind the search form about `common\models\WebFormSubmission`.
 */
class WebFormSubmissionSearch extends WebFormSubmission
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'id', 'web_form', 'created_at', 'updated_at' ], 'integer' ],
            [ [ 'language', 'submission' ], 'safe' ],
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
        $query = WebFormSubmission::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'web_form'   => $this->web_form,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere([ 'like', 'language', $this->language ])
              ->andFilterWhere([ 'like', 'submission', $this->submission ]);

        return $dataProvider;
    }
}