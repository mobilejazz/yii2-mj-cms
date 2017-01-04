<?php

namespace mobilejazz\yii2\cms\backend\models\search;

use mobilejazz\yii2\cms\common\models\Locale;
use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * LocaleSearch represents the model behind the search form about `common\models\Locale`.
 */
class LocaleSearch extends Locale
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'id' ], 'integer' ],
            [ [ 'lang', 'label' ], 'safe' ],
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
        $query = Locale::find();

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
            'default'    => $this->default,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere([ 'like', 'lang', $this->lang ])
              ->andFilterWhere([ 'like', 'label', $this->label ]);

        return $dataProvider;
    }
}