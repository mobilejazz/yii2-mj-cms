<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model mobilejazz\yii2\cms\backend\modules\i18n\models\search\I18nSourceMessageSearch */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="i18n-source-message-search">

    <?php $form = ActiveForm::begin([
        'action' => [ 'index' ],
        'method' => 'get',
    ]); ?>

    <?php echo $form->field($model, 'id') ?>

    <?php echo $form->field($model, 'category') ?>

    <?php echo $form->field($model, 'message') ?>

    <div class="form-group">
        <?php echo Html::submitButton('Search', [ 'class' => 'btn btn-primary' ]) ?>
        <?php echo Html::resetButton('Reset', [ 'class' => 'btn btn-primary' ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
