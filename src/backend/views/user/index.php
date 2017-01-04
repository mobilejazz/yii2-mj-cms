<?php

use mobilejazz\yii2\cms\backend\widgets\ExpandedGridView;
use mobilejazz\yii2\cms\common\models\User;
use mobilejazz\yii2\cms\common\widgets\BoxPanel;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $this         yii\web\View
 * @var $searchModel  mobilejazz\yii2\cms\backend\models\search\UserSearch
 * @var $dataProvider yii\data\ActiveDataProvider
 */

$this->title                     = Yii::t('backend', 'Users');
$this->params[ 'breadcrumbs' ][] = $this->title;
BoxPanel::begin([
    'display_header' => false,
]);
echo ExpandedGridView::widget([
    'layout'       => '{pager}{items}',
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'searchModel'  => 'UserSearch',
    'searchField'  => 'email',
    'bulk_actions' => [
        ''           => \Yii::t('backend', 'Bulk Actions'),
        'delete'     => \Yii::t('backend', 'Delete'),
        'activate'   => \Yii::t('backend', 'Activate'),
        'deactivate' => \Yii::t('backend', 'Deactivate'),
        'translator' => \Yii::t('backend', 'Make Translator'),
        'editor'     => \Yii::t('backend', 'Make Editor'),
        'admin'      => \Yii::t('backend', 'Make Admin')
    ],
    'columns'      => [
        [
            'class' => 'yii\grid\CheckboxColumn',
        ],
        [
            'label'  => Yii::t('backend', 'Username'),
            'value'  => function ($model)
            {
                /** @var \common\models\User $model */
                return Html::a($model->name, [
                    'user/update/?id=' . $model->id,
                ]);
            },
            'format' => 'html',
        ],
        [
            'attribute' => 'role',
            'label'     => Yii::t('backend', 'Role'),
            'value'     => function ($model)
            {
                /** @var User $model */
                return Html::a($model->getRole(), [ 'user/index/?UserSearch[role]=' . $model->role ]);
            },
            'filter'    => false,
            'format'    => 'html',
        ],
        [
            'attribute' => 'status',
            'value'     => function ($model)
            {
                return $model->getStatus();
            },
            'filter'    => false,
        ],
        [
            'label' => Yii::t('backend', 'Name'),
            'value' => function ($model)
            {
                /** @var User $model */
                return $model->name . " " . $model->last_name;
            },
        ],
        [
            'attribute' => 'email',
            'filter'    => false,
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
                    return Html::a('Edit', $url);
                },
                'delete' => function ($url, $model, $key)
                {
                    return Html::a('Delete', $url, [
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
]);
$script = <<< JS
// USER SEARCH BY  NAME.
$('#user-text-box').keypress(function (e) {
    var code = e.keyCode || e.which;
    if (code == 13) {
        var text = $('#user-text-box').val();
        window.location.href = "?UserSearch[name]=" + text;
    }
});
$('#user-search-btn').click(function () {
    var text = $('#user-text-box').val();
    window.location.href = "?UserSearch[name]=" + text;
});
JS;
$this->registerJs($script);

