<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View                        $this
 * @var mobilejazz\yii2\cms\backend\models\search\WebFormSearch $model
 * @var yii\widgets\ActiveForm              $form
 */
?>

<div class="web-form-search">

    <?php $form = ActiveForm::begin([
        'action' => [ 'index' ],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'view') ?>

    <?= $form->field($model, 'author_id') ?>

    <?= $form->field($model, 'updater_id') ?>

    <?= $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', [ 'class' => 'btn btn-primary' ]) ?>
        <?= Html::resetButton('Reset', [ 'class' => 'btn btn-default' ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
