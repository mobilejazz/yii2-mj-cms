<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $model mobilejazz\yii2\cms\common\models\User */

$this->title                     = 'Update ' . $model->getFullName();
$this->params[ 'breadcrumbs' ][] = [ 'label' => 'Users', 'url' => [ 'index' ] ];
$this->params[ 'breadcrumbs' ][] = [ 'label' => $model->name, 'url' => [ 'view', 'id' => $model->id ] ];
$this->params[ 'breadcrumbs' ][] = 'Update';
?>
<div class="box">
    <div class="box-body">
        <div class="form-group">
            <?= Html::a('<i class="fa fa-plus icon-margin small"></i>' . Yii::t('backend', 'Add New'), Url::to([ 'create' ]), [
                'class' => 'btn btn-primary',
            ]) ?>
        </div>
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>
