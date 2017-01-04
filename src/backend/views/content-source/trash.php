<?php

use mobilejazz\yii2\cms\backend\widgets\ExpandedGridView;
use mobilejazz\yii2\cms\common\models\ContentSource;
use mobilejazz\yii2\cms\common\models\Views;
use mobilejazz\yii2\cms\common\widgets\BoxPanel;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/**
 * @var yii\web\View                                     $this
 * @var yii\data\ActiveDataProvider                      $dataProvider
 * @var mobilejazz\yii2\cms\backend\models\search\ContentSourceTrashedSearch $searchModel
 */

$this->title                     = Yii::t('backend', 'Trashed Contents');
$this->params[ 'breadcrumbs' ][] = [
    'label' => Yii::t('backend', 'Contents'),
    'url'   => [ 'index' ],
];
$this->params[ 'breadcrumbs' ][] = $this->title;
BoxPanel::begin([
    'display_header' => false,
]);
Pjax::begin([
    'id'                 => 'pjax-content-overview',
    'enableReplaceState' => false,
    'linkSelector'       => '#pjax-main ul.pagination a, th a',
    'clientOptions'      => [ 'pjax:success' => 'function(){alert("yo")}' ],
])
?>
<!-- Main -->
<?= ExpandedGridView::widget([
    'layout'               => '{items}{pager}',
    'dataProvider'         => $dataProvider,
    'create'               => Html::a(Yii::t('backend', 'See Contents'), [ 'index' ], [ 'class' => 'btn btn-default' ]),
    'bulk_actions'         => [
        ''        => Yii::t('backend', 'Bulk Actions'),
        'restore' => Yii::t('backend', 'Restore (As Draft)'),
        'delete'  => Yii::t('backend', 'Delete Forever'),
    ],
    'bulk_action_base_url' => '/admin/content-source',
    'columns'              => [
        [
            'class' => 'yii\grid\CheckboxColumn',
        ],
        [
            'attribute' => 'title',
            'label'     => Yii::t('backend', 'Title'),
            'value'     => function ($model)
            {
                /** @var ContentSource $model */
                return $model->getTitle();
            },
            'format'    => 'raw',
            'filter'    => false,
        ],
        [
            'attribute' => 'view',
            'label'     => Yii::t('backend', 'Type of Content'),
            'value'     => function ($model)
            {
                return Views::getViewName($model->view);
            },
            'filter'    => false,
            'format'    => 'raw',
        ],
        [
            'attribute' => 'author_id',
            'value'     => function ($model)
            {
                $author = $model->author_id ? $model->author->name : null;

                return $author;
            },
            'filter'    => false,
            'format'    => 'raw',
        ],
        [
            'attribute' => 'updated_at',
            'label'     => Yii::t('backend', 'Date'),
            'value'     => function ($model)
            {
                if ($model->updated_at > $model->created_at)
                {
                    /** @var ContentSource $model */
                    return Yii::t("backend", "Updated on ") . date("d/m/y", $model->updated_at) . " " . Yii::t("backend",
                        "by") . " " . $model->updater->name;
                }

                return Yii::t("backend", "Published on ") . date("d/m/y", $model->updated_at) . " " . Yii::t("backend",
                    "by") . " " . $model->author->name;;
            },
        ],
        [
            'attribute' => 'slug',
            'value'     => function ($model)
            {
                /** @var ContentSource $model */
                return $model->getCurrentSlug(Yii::$app->language)->slug;
            },
        ],
        [
            'class'          => 'yii\grid\ActionColumn',
            'template'       => '{restore} | {delete}',
            'header'         => Yii::t('backend', 'Action'),
            'urlCreator'     => function ($action, $model, $key, $index)
            {
                // using the column name as key, not mapping to 'id' like the standard generator
                $params      = is_array($key) ? $key : [ $model->primaryKey()[ 0 ] => (string) $key ];
                $params[ 0 ] = \Yii::$app->controller->id ? \Yii::$app->controller->id . '/' . $action : $action;

                return Url::toRoute($params);
            },
            'buttons'        => [
                'restore' => function ($url, $model, $key)
                {
                    return Html::a(Yii::t('backend', 'Restore'), $url, [
                        'data'   => [
                            'confirm' => 'Are you sure you want to restore (as a Draft) this content?',
                        ],
                        'target' => '_blank',
                    ]);
                },
                'delete'  => function ($url, $model, $key)
                {
                    return Html::a(Yii::t('backend', 'Delete Forever'), $url, [
                        'data' => [
                            'confirm' => Yii::t('backend', 'Are you sure that you want to permanently delete this content?'),
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
BoxPanel::end()
?>
