<?php

namespace mobilejazz\yii2\cms\backend\models\search;

use mobilejazz\yii2\cms\common\models\WebForm;
use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * WebFormSearch represents the model behind the search form about `common\models\WebForm`.
 */
class WebFormSearch extends WebForm
{

    public $title;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'id', 'author_id', 'updater_id' ], 'integer' ],
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
        $query = WebForm::find();
        $query->joinWith([ 'webFormDetails' ]);

        $pageSize = isset($params[ 'per-page' ]) ? intval($params[ 'per-page' ]) : 20;

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [ 'pageSize' => $pageSize, ],
        ]);

        $dataProvider->sort->attributes[ 'title' ] = [
            'asc'  => [
                'web_form_detail.title' => SORT_ASC,
            ],
            'desc' => [
                'web_form_detail.title' => SORT_DESC,
            ],
        ];

        $this->load($params);

        if (!$this->validate())
        {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andWhere([
            'web_form_detail.language' => \Yii::$app->language,
        ]);

        $query->andFilterWhere([
            'id'         => $this->id,
            'author_id'  => $this->author_id,
            'updater_id' => $this->updater_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere([
            'like',
            'web_form_detail.title',
            $this->title,
        ]);

        return $dataProvider;
    }
}