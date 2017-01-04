<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel mobilejazz\yii2\cms\backend\models\search\WidgetMenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                     = Yii::t('backend', 'Widget Menus');
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="widget-menu-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php echo Html::a(Yii::t('backend', 'Create Widget Menu'), [ 'create' ], [ 'class' => 'btn btn-success' ]) ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            [ 'class' => 'yii\grid\SerialColumn' ],

            'title',
            'key',
            [
                'class'     => \common\grid\EnumColumn::className(),
                'attribute' => 'status',
                'enum'      => [
                    'Disabled',
                    'Enabled',
                ],
            ],

            [ 'class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}' ],
        ],
    ]); ?>

</div>
