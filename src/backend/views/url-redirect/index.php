<?php

use mobilejazz\yii2\cms\backend\widgets\LinkPager;
use mobilejazz\yii2\cms\common\widgets\BoxPanel;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/**
 * @var yii\web\View                            $this
 * @var yii\data\ActiveDataProvider             $dataProvider
 * @var mobilejazz\yii2\cms\backend\models\search\UrlRedirectSearch $searchModel
 */

$this->title                     = 'Url Redirects';
$this->params[ 'breadcrumbs' ][] = $this->title;
Pjax::begin([
    'id'                 => 'pjax-main',
    'enableReplaceState' => false,
    'linkSelector'       => '#pjax-main ul.pagination a, th a',
    'clientOptions'      => [ 'pjax:success' => 'function(){alert("yo")}' ],
]);
BoxPanel::begin([
    'display_header' => false,
]);
?>
    <!-- Quick tools -->
    <div class="form-inline">
        <div class="form-group">
            <?= Html::a('<i class="fa fa-plus icon-margin small"></i> ' . Yii::t('backend', 'Add New'), false, [
                'data-value' => 'create',
                'label'      => Yii::t('backend', 'Create a new URL redirect'),
                'class'      => 'showModalButton btn btn-primary',
            ]) ?>
        </div>

        <div class="form-group">
            <?= Html::dropDownList('bulk-action', null, [
                ''       => Yii::t('backend', 'Bulk Actions'),
                'delete' => Yii::t('backend', 'Delete'),
            ], [
                'id'    => 'bulk-dropdown',
                'class' => 'form-control',
            ]) ?>
            <?= Html::button("<i class=\"fa fa-check icon-margin small\"></i> " . Yii::t("backend", "Apply"), [
                'id'    => 'bulk-action-submit',
                'class' => 'btn btn-default',
            ]) ?>
        </div>
    </div>

    <!-- Main -->
<?= GridView::widget([
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
    'tableOptions'     => [ 'class' => 'table table-striped add-top-margin' ],
    'headerRowOptions' => [ 'class' => '' ],
    'columns'          => [
        [
            'class' => 'yii\grid\CheckboxColumn',
        ],
        [
            'attribute' => 'origin_slug',
            'label'     => Yii::t('backend', 'Origin'),
            'value'     => function ($model)
            {
                return Html::a($model->origin_slug, $model->origin_slug, [
                    'target' => '_blank',
                    'class'  => '',
                ]);
            },
            'format'    => 'raw',
        ],
        [
            'attribute' => 'origin_slug',
            'label'     => Yii::t('backend', 'Destination'),
            'value'     => function ($model)
            {
                return Html::a($model->destination_slug, $model->destination_slug, [
                    'target' => '_blank',
                    'class'  => '',
                ]);
            },
            'format'    => 'raw',
        ],
        [
            'attribute' => 'updated_at',
            'label'     => Yii::t('backend', 'Date'),
            'value'     => function ($model)
            {
                /** @var \common\models\UrlRedirect $model */
                if ($model->updated_at > $model->created_at)
                {
                    return Yii::t("backend", "Updated on ") . date("d/m/y", $model->updated_at);
                }

                return Yii::t("backend", "Published on ") . date("d/m/y", $model->updated_at);
            },
        ],
        [
            'class'          => 'yii\grid\ActionColumn',
            'header'         => Yii::t('backend', 'Edit'),
            'template'       => '{update} | {delete}',
            'urlCreator'     => function ($action, $model, $key, $index)
            {
                // using the column name as key, not mapping to 'id' like the standard generator
                $params      = is_array($key) ? $key : [ $model->primaryKey()[ 0 ] => (string) $key ];
                $params[ 0 ] = \Yii::$app->controller->id ? \Yii::$app->controller->id . '/' . $action : $action;

                return Url::toRoute($params);
            },
            'buttons'        => [
                'delete' => function ($url, $model, $key)
                {
                    return Html::a(Yii::t('backend', 'Delete'), $url, [
                        'data' => [
                            'confirm' => Yii::t('backend', 'Are you sure you want to delete this redirect?'),
                            'method'  => 'post',
                        ],
                    ]);
                },
                'update' => function ($url, $model, $key)
                {
                    return Html::a('Edit', false, [
                        'class'      => 'showModalButton',
                        'data-value' => $url,
                        'label'      => Yii::t('backend', 'Update Redirect'),
                        'style'      => 'cursor: pointer;',
                    ]);
                },
            ],
            'contentOptions' => [ 'nowrap' => 'nowrap' ],
        ],

    ],
]);
BoxPanel::end();
Pjax::end() ?>