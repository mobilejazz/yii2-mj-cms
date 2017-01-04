<?php

namespace mobilejazz\yii2\cms\backend\models\search;

use mobilejazz\yii2\cms\common\models\WidgetMenu;
use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * WidgetMenuSearch represents the model behind the search form about `common\models\WidgetMenu`.
 */
class WidgetMenuSearch extends WidgetMenu
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'id', 'status' ], 'integer' ],
            [ [ 'key', 'title', 'items' ], 'safe' ],
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
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = WidgetMenu::find();

        $pageSize = isset($params[ 'per-page' ]) ? intval($params[ 'per-page' ]) : 20;

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [ 'pageSize' => $pageSize, ],
        ]);

        if (!($this->load($params) && $this->validate()))
        {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id'     => $this->id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere([ 'like', 'key', $this->key ])
              ->andFilterWhere([ 'like', 'title', $this->title ])
              ->andFilterWhere([
                  'like',
                  'items',
                  $this->items,
              ]);

        return $dataProvider;
    }
}
