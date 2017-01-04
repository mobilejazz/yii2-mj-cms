<?php

/* @var $this yii\web\View */
/* @var $model mobilejazz\yii2\cms\common\models\User */

$this->title                     = Yii::t('backend', 'Create a new User');
$this->params[ 'breadcrumbs' ][] = [ 'label' => 'Users', 'url' => [ 'index' ] ];
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="box">
    <div class="box-body">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>