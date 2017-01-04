<?php

use mobilejazz\yii2\cms\backend\widgets\ExpandedGridView;
use mobilejazz\yii2\cms\backend\widgets\LinkPager;
use mobilejazz\yii2\cms\common\models\User;
use mobilejazz\yii2\cms\common\models\WebForm;
use mobilejazz\yii2\cms\common\widgets\BoxPanel;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/**
 * @var yii\web\View                                            $this
 * @var yii\data\ActiveDataProvider                             $dataProvider
 * @var mobilejazz\yii2\cms\backend\models\search\WebFormSearch $searchModel
 */

$this->title                     = Yii::t('backend', 'Web Forms');
$this->params[ 'breadcrumbs' ][] = $this->title;
BoxPanel::begin([
    'display_header' => false,
]);
Pjax::begin([
    'id'                 => 'pjax-web-form-overview',
    'enableReplaceState' => false,
    'linkSelector'       => '#pjax-main ul.pagination a, th a',
    'clientOptions'      => [ 'pjax:success' => 'function(){alert("yo")}' ],
]);
echo ExpandedGridView::widget([
    'layout'           => '{pager}{items}',
    'dataProvider'     => $dataProvider,
    'pager'            => [
        'class'   => LinkPager::className(),
        'options' => [
            'class' => 'pagination',
            'style' => 'display: inline',
        ],
    ],
    'filterModel'      => $searchModel,
    'searchModel'  => 'WebFormSearch',
    'searchField'  => 'title',
    'tableOptions'     => [ 'class' => 'table table-striped' ],
    'headerRowOptions' => [ 'class' => '' ],
    'columns'          => [
        [
            'class' => 'yii\grid\CheckboxColumn',
        ],
        [
            'attribute' => 'title',
            'label'     => Yii::t('backend', 'Title'),
            'value'     => function ($model)
            {
                /** @var WebForm $model */
                return Html::a($model->getTitle(), Url::to([ 'update', 'id' => $model->id ]));
            },
            'format'    => 'html',
            //'filter'    => '',
        ],
        [
            'attribute' => 'author_id',
            'value'     => function ($model)
            {
                $author = $model->author_id ? $model->author->name : null;

                return Html::a($author, [ 'web-form/index/?WebFormSearch[author]=' . $author ]);
            },
            'filter'    => ArrayHelper::map(User::find()
                                                ->all(), 'id', 'name'),
            'format'    => 'html',
        ],
        [
            'attribute' => 'web_form_submission',
            'value'     => function ($model)
            {
                /** @var WebForm $model */
                $count = $model->getWebFormSubmissions()
                               ->count();

                return Html::a(Yii::t('backend', '{number} Submissions', [ 'number' => $count, ]), [
                    'web-form/submissions/?WebFormSubmissionSearch[web_form]=' . $model->id,
                ]);
            },
            'filter'    => false,
            'format'    => 'html',
        ],
        [
            'attribute' => 'updated_at',
            'label'     => Yii::t('backend', 'Date'),
            'value'     => function ($model)
            {
                if ($model->updated_at > $model->created_at)
                {
                    /** @var WebForm $model */
                    return Yii::t("backend", "Updated on ") . date("d/m/y", $model->updated_at) . " " . Yii::t("backend",
                            "by") . " " . $model->updater->name;
                }

                return Yii::t("backend", "Published on ") . date("d/m/y", $model->updated_at) . " " . Yii::t("backend",
                        "by") . " " . $model->author->name;;
            },
        ],
        [
            'class'          => 'yii\grid\ActionColumn',
            'header'         => Yii::t('backend', 'Action'),
            'template'       => '{update} | {delete}',
            'urlCreator'     => function ($action, $model, $key, $index)
            {
                // using the column name as key, not mapping to 'id' like the standard generator
                $params      = is_array($key) ? $key : [ $model->primaryKey()[ 0 ] => (string) $key ];
                $params[ 0 ] = \Yii::$app->controller->id ? \Yii::$app->controller->id . '/' . $action : $action;

                return Url::toRoute($params);
            },
            'buttons'        => [
                'submissions' => function ($url, $model, $key)
                {
                    return Html::a(Yii::t('backend', 'Submissions'), $url);
                },
                'update'      => function ($url, $model, $key)
                {
                    return Html::a(Yii::t('backend', 'Update'), $url);
                },
                'delete'      => function ($url, $model, $key)
                {
                    return Html::a(Yii::t('backend', 'Delete'), $url, [
                        'data' => [
                            'confirm' => Yii::t('backend', 'Are you sure that you want to delete this Form? '),
                        ],
                    ]);
                },
            ],
            'contentOptions' => [ 'nowrap' => 'nowrap' ],
        ],
    ],
]); ?>
<?php
Pjax::end();
BoxPanel::end();
?>
