<?php

namespace mobilejazz\yii2\cms\backend\models\search;

use mobilejazz\yii2\cms\common\models\Menu;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * MenuSearch represents the model behind the search form about `common\models\Menu`.
 */
class MenuSearch extends Menu
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'id' ], 'integer' ],
            [ [ 'key', 'class' ], 'safe' ],
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
        $query = Menu::find();

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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere([ 'like', 'key', $this->key ])
              ->andFilterWhere([ 'like', 'class', $this->class ]);

        return $dataProvider;
    }
}