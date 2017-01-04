<?php

namespace mobilejazz\yii2\cms\frontend\models;

use mobilejazz\yii2\cms\common\models\ContentSource;
use mobilejazz\yii2\cms\common\models\User;
use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\BadRequestHttpException;

/**
 * ContentSourceSearch represents the model behind the search form about `common\models\ContentSource`.
 */
class ContentSourceSearch extends ContentSource
{

    public $searchString;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'searchString' ], 'safe' ],
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
     * This is what we should be looking for:
     *
     * SELECT content_source.*
     * FROM content_source
     * INNER JOIN content_slug ON content_source.id = content_slug.content_id
     * WHERE content_slug.title="Home"
     * UNION
     * SELECT content_source.*
     * FROM content_source
     * INNER JOIN content_component ON content_component.content_id = content_source.id
     * INNER JOIN component_field ON content_component.id = component_field.component_id
     * WHERE component_field.text ="INFORMATION ABOUT"
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $this->load($params);

        if (!$this->validate())
        {
            new BadRequestHttpException(\Yii::t('app', 'The provided search string is invalid'));
        }

        $query = ContentSource::find();

        // STATUS DELETED, DO NOT DISPLAY.
        $query->where([ 'not like', 'status', self::STATUS_DELETED ]);

        // If USER IS GUEST
        if (\Yii::$app->user->isGuest)
        {
            $query->andWhere([ 'not like', 'status', self::STATUS_DRAFT ]);
            $query->andWhere([ 'not like', 'status', self::STATUS_PRIVATE_CONTENT ]);
        }
        // NOT VALIDATED USERS AND DRAFT HANDLES.
        else
        {
            /** @var User $user */
            $user = \Yii::$app->user->getIdentity();
            if ($user->status == User::STATUS_AWAITING_VALIDATION || $user->status == User::STATUS_INVALIDATED)
            {
                $query->andWhere([ 'not like', 'status', self::STATUS_PRIVATE_CONTENT ]);
            }
            if ($user->role == User::ROLE_USER)
            {
                $query->andWhere([ 'not like', 'status', self::STATUS_DRAFT ]);
            }
        }

        // LOOK THROUGH THE SLUGS.
        $query->innerJoin("content_slug", "content_source.id = content_slug.content_id")
              ->where([ 'content_slug.language' => \Yii::$app->language ])
              ->filterWhere([ 'like', 'content_slug.title', $this->searchString ])
              ->orFilterWhere([ 'like', 'content_slug.slug', $this->searchString ]);

        $query2 = (new Query())->select("content_source.*")
                               ->from("content_source")
                               ->innerJoin("content_component", "content_component.content_id = content_source.id")
                               ->innerJoin("component_field", "content_component.id = component_field.component_id")
                               ->where([ 'content_component.language' => \Yii::$app->language ])
                               ->filterWhere([ 'like', 'component_field.text', $this->searchString ]);

        $query->union($query2);

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

        return $dataProvider;
    }
}