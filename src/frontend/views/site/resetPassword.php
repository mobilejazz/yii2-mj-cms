<?php
use mobilejazz\yii2\cms\frontend\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \mobilejazz\yii2\cms\frontend\models\ResetPasswordForm */

$this->title                     = \Yii::t('app', 'Reset password');
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<!-- Page title -->
<div id="page-title" class="row" style="margin-top: 25px;">
    <div class="small-12 columns">
        <h1><span class="text-light"><?= \Yii::t('app', 'Password'); ?></span> <?= \Yii::t('app', 'Reset'); ?></h1>
    </div>
</div>
<div id="page-content" class="row">
    <div class="small-12 medium-9 large-6 medium-centered columns">
        <?php $form = ActiveForm::begin([ 'id' => 'reset-password-form' ]); ?>

        <h2 class="form-title"><?= \Yii::t('app', 'Please choose your new password'); ?>:</h2>

        <?= $form->field($model, 'password', [
            'labelOptions' => [ 'disabled' => true, ],
            'inputOptions' => [
                'placeholder' => \Yii::t('app', 'New Password'),
            ],
        ])->label(false)->passwordInput() ?>
        <!-- Submit -->
        <div class="form-group text-right">
            <?= Html::submitButton(\Yii::t('app', 'Reset Password'), [ 'class' => 'btn btn-primary', 'name' => 'login-button' ]) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
