<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel mobilejazz\yii2\cms\backend\modules\i18n\models\search\I18nSourceMessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                     = Yii::t('backend', 'Messages');
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="i18n-source-message-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php echo Html::a('Create a new Message', [ 'create' ], [ 'class' => 'btn btn-success' ]) ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            [ 'class' => 'yii\grid\SerialColumn' ],

            'id',
            'category',
            'message:ntext',

            [ 'class' => 'yii\grid\ActionColumn' ],
        ],
    ]); ?>

</div>
