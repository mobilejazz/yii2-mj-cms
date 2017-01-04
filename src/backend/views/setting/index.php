<?php

use mobilejazz\yii2\cms\backend\widgets\ExpandedGridView;
use mobilejazz\yii2\cms\common\widgets\BoxPanel;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel  mobilejazz\yii2\cms\backend\models\search\SettingSearch */

$this->title = 'Settings';
$this->params['breadcrumbs'][] = $this->title;


BoxPanel::begin([
    'display_header' => false,
]);


?>


<?php Pjax::begin(); ?>    <?= ExpandedGridView::widget([
    'layout' => '{pager}{items}',
    'dataProvider' => $dataProvider,
    'searchModel' => 'SettingSearch',
    'searchField' => 'id',
    'columns' => [
        [
            'class' => 'yii\grid\CheckboxColumn',
        ],

        'id',
        'value',
        'created_at',
        'updated_at',

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
]); ?>
<?php Pjax::end(); ?>

<?php BoxPanel::end(); ?>
