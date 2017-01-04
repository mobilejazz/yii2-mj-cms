<?php

namespace mobilejazz\yii2\cms\backend\modules\i18n\models\search;

use mobilejazz\yii2\cms\backend\modules\i18n\models\I18nMessage;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * I18nMessageSearch represents the model behind the search form about `backend\modules\i18n\models\I18nMessage`.
 */
class I18nMessageSearch extends I18nMessage
{

    public $missing_only = false;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'id', 'created_at', 'updated_at' ], 'integer' ],
            [ [ 'language', 'translation', 'sourceMessage', 'category' ], 'safe' ],
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
        $query = I18nMessage::find()
                            ->where([ 'language' => \Yii::$app->language ])
                            ->with('sourceMessageModel')
                            ->joinWith('sourceMessageModel');

        $pageSize = isset($params[ 'per-page' ]) ? intval($params[ 'per-page' ]) : 20;

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [ 'pageSize' => $pageSize, ],
        ]);

        // TODO (Pol) delete whenever we want more categories.
//        $query->andFilterWhere([
//            '{{%i18n_source_message}}.category' => [ 'app', 'url' ],
//        ]);

        if ($this->missing_only)
        {
            $query->andWhere(([ 'translation' => null ]))
                  ->orWhere([ 'translation' => '', ]);
        }

        $query->andWhere([ 'language' => \Yii::$app->language ]);
        $query->orderBy([ 'updated_at' => SORT_DESC ]);

        $loaded = $this->load($params);
        $valid  = $this->validate();
        if (!($loaded && $valid))
        {
            return $dataProvider;
        }

        $query->andFilterWhere([
            '{{%i18n_source_message}}.id' => $this->id,
        ]);

        if (!$this->missing_only)
        {
            $query->andFilterWhere([
                'like',
                'translation',
                $this->translation
            ]);
        }
        $query->andFilterWhere([ 'like', '{{%i18n_source_message}}.message', $this->sourceMessage ])
              ->andFilterWhere([
                  'like',
                  '{{%i18n_source_message}}.category',
                  $this->category
              ]);

        $query->andWhere([ 'language' => \Yii::$app->language ]);

        return $dataProvider;
    }
}
