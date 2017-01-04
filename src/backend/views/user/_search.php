<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var $this  yii\web\View
 * @var $model mobilejazz\yii2\cms\backend\models\search\UserSearch
 * @var $form  yii\widgets\ActiveForm
 */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => [ 'index' ],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'auth_key') ?>

    <?= $form->field($model, 'password_hash') ?>

    <?= $form->field($model, 'password_reset_token') ?>

    <?php // echo $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'last_name') ?>

    <?php // echo $form->field($model, 'role') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'last_login') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', [ 'class' => 'btn btn-primary' ]) ?>
        <?= Html::resetButton('Reset', [ 'class' => 'btn btn-primary' ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
