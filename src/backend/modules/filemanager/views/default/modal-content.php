<?php

use mobilejazz\yii2\cms\backend\modules\filemanager\models\Mediafile;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;

/**
 * @var $this         yii\web\View
 * @var $model        Mediafile
 * @var $dataProvider ActiveDataProvider
 */

$this->title                     = Yii::t('backend', 'File manager');
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<!-- Quick tools -->
<div class="form-inline">
    <div class="form-group">
        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('backend', 'Add Files'),
            [ '/filemanager/default/uploadmanager-content' ], [ 'class' => 'btn btn-primary' ]) ?>
    </div>
</div>
<div class="row" style="margin-top: 10px;">
    <?= $this->render('_filemanager', [
        'model'        => $model,
        'dataProvider' => $dataProvider,
    ]); ?>
</div>
