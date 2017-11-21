<?php

use mobilejazz\yii2\cms\backend\widgets\ExpandedGridView;
use mobilejazz\yii2\cms\common\models\ContentSource;
use mobilejazz\yii2\cms\common\models\User;
use mobilejazz\yii2\cms\common\models\Views;
use mobilejazz\yii2\cms\common\widgets\BoxPanel;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/**
 * @var yii\web\View                                                  $this
 * @var yii\data\ActiveDataProvider                                   $dataProvider
 * @var mobilejazz\yii2\cms\backend\models\search\ContentSourceSearch $searchModel
 * @var ContentSource[]                                               $deleted
 */

$this->title                     = Yii::t('backend', 'Contents');
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
<?php if (count($deleted) > 0): ?>
    <div class="form-group pull-right">
        <?= Html::a("<i class=\"fa fa-trash icon-margin small\"></i> " . Yii::t('backend', 'Trash') . " (" . count($deleted) . ")", [
            'trash',
        ], [
            'class' => 'btn btn-default',
        ]) ?>
    </div>
<?php endif; ?>
<!-- Main -->
<?= ExpandedGridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'searchModel'  => 'ContentSourceSearch',
    'searchField'  => 'title',
    'bulk_actions' => [
        ''        => \Yii::t('backend', 'Bulk Actions'),
        'publish' => \Yii::t('backend', 'Make Public (Visible for All)'),
        'private' => \Yii::t('backend', 'Make Private For Validated Users'),
        'draft'   => \Yii::t('backend', 'Make Draft (Visible for Admins)'),
        'delete'  => \Yii::t('backend', 'Delete (Remove)'),
    ],
    'columns'      => [
        [
            'class' => 'yii\grid\CheckboxColumn',
        ],
        [
            'attribute' => 'title',
            'label'     => Yii::t('backend', 'Title'),
            'value'     => function ($model)
            {
                /** @var ContentSource $model */
                return Html::a($model->getTitle(), [ 'content-source/update', 'id' => $model->id ]);
            },
            'format'    => 'html',
        ],
        [
            'attribute' => 'view',
            'label'     => Yii::t('backend', 'Type of Content'),
            'value'     => function ($model)
            {
                // return Html::a(Views::getViewName($model->view), [ 'content-source/index/?ContentSourceSearch[view]=' . $model->view ]);
                return Views::getViewName($model->view);
            },
            'filter'    => Views::asMap(),
            'format'    => 'raw',
        ],
        [
            'attribute' => 'author_id',
            'value'     => function ($model)
            {
                $author = $model->author_id ? $model->author->name : null;

                // return Html::a($author, [ 'content-source/index/?ContentSourceSearch[author]=' . $author ]);
                return $author;
            },
            'filter'    => ArrayHelper::map(User::find()
                                                ->all(), 'id', 'name'),
            'format'    => 'raw',
        ],
        [
            'attribute' => 'status',
            'value'     => function ($model)
            {
                /** @var $model ContentSource */
                return $model->getStatus();
            },
            'filter'    => ContentSource::statusAsMap(),
            'format'    => 'html',
        ],
        [
            'attribute' => 'is_homepage',
            'label'     => \Yii::t('backend', 'Homepage'),
            'filter'    => false,
            'format'    => 'boolean',
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
            'header'         => Yii::t('backend', 'Action'),
            'template'       => '{view} | {update} | {delete}',
            'urlCreator'     => function ($action, $model, $key, $index)
            {
                if ($action == 'view')
                {
                    /** @var ContentSource $model */

                    $lang = Yii::$app->language;
                    /** @var string $slug */
                    $slug = $model->getCurrentContentSlug($lang);

                    $link = $this->context->module->urlManagerFrontend->createBaseUrl('cmsfrontend/site/content', [
                        'lang' => Yii::$app->language,
                        'slug' => $slug,
                        $this->context->module->previewService->url_param => $this->context->module->previewService->getToken()
                    ]);

                    return $link;
                }
                else
                {
                    // using the column name as key, not mapping to 'id' like the standard generator
                    $params      = is_array($key) ? $key : [ $model->primaryKey()[ 0 ] => (string) $key ];
                    $params[ 0 ] = \Yii::$app->controller->id ? \Yii::$app->controller->id . '/' . $action : $action;

                    return Url::toRoute($params);
                }
            },
            'buttons'        => [
                'update' => function ($url, $model, $key)
                {
                    return Html::a('Update', $url);
                },
                'view'   => function ($url, $model, $key)
                {
                    return Html::a('View', $url, [ 'target' => '_blank' ]);
                },
                'delete' => function ($url, $model, $key)
                {
                    return Html::a('Delete', $url, [
                        'data' => [
                            'confirm' => Yii::t('backend', 'Are you sure that you want to delete this content'),
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
