<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View                $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title                     = Yii::t('backend', 'Menus');
$this->params[ 'breadcrumbs' ][] = $this->title;
\yii\widgets\Pjax::begin([
    'id'                 => 'pjax-main',
    'enableReplaceState' => false,
    'linkSelector'       => '#pjax-main ul.pagination a, th a',
    'clientOptions'      => [ 'pjax:success' => 'function(){alert("yo")}' ],
])
?>
    <div class="box">
        <div class="box-body">
            <!-- Quick tools -->
            <div class="form-inline">
                <div class="form-group">
                    <?= Html::a('<i class="fa fa-plus icon-margin small"></i> ' . Yii::t('backend', 'Add New'), [ 'create' ],
                        [ 'class' => 'btn btn-primary' ]) ?>
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
                    'class'   => mobilejazz\yii2\cms\backend\widgets\LinkPager::className(),
                    'options' => [
                        'class' => 'pagination',
                        'style' => 'display: inline',
                    ],
                ],
                'tableOptions'     => [ 'class' => 'table table-striped add-top-margin' ],
                'headerRowOptions' => [ 'class' => '' ],
                'columns'          => [
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                    ],
                    [
                        'attribute' => 'key',
                        'label'     => Yii::t('backend', 'Key'),
                    ],
                    [
                        'attribute' => 'class',
                        'label'     => Yii::t('backend', 'CSS'),
                    ],
                    [
                        'label' => Yii::t('backend', 'Menu Items'),
                        'value' => function ($model)
                        {

                            /** @var \common\models\Menu $model */
                            return $model->getMenuItems()
                                         ->count();
                        },
                    ],
                    [
                        'attribute' => 'updated_at',
                        'label'     => Yii::t('backend', 'Date'),
                        'value'     => function ($model)
                        {
                            /** @var \common\models\Menu $model */
                            if ($model->updated_at > $model->created_at)
                            {
                                return Yii::t("backend", "Updated on ") . date("d/m/y", $model->updated_at);
                            }

                            return Yii::t("backend", "Created on ") . date("d/m/y", $model->updated_at);
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
                            'update' => function ($url, $model, $key)
                            {
                                return Html::a(Yii::t('backend', 'Edit'), $url);
                            },
                            'delete' => function ($url, $model, $key)
                            {
                                return Html::a(Yii::t('backend', 'Delete'), $url, [
                                    'data' => [
                                        'confirm' => Yii::t('backend', 'Are you sure you want to delete this menu?'),
                                        'method'  => 'post',
                                    ],
                                ]);
                            },
                        ],
                        'contentOptions' => [ 'nowrap' => 'nowrap' ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
<?php \yii\widgets\Pjax::end() ?>