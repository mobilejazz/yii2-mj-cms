<?php
use mobilejazz\yii2\cms\frontend\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \mobilejazz\yii2\cms\frontend\models\PasswordResetRequestForm */

$this->title                     = \Yii::t('app', 'Request password reset');
$this->params[ 'breadcrumbs' ][] = $this->title;
?>

<!-- Page title -->
<div id="page-title" class="row" style="margin-top: 25px;">
    <div class="small-12 columns">
        <h1><span class="text-light"><?= \Yii::t('app', 'Request'); ?></span> <?= \Yii::t('app', 'Password Reset'); ?></h1>
    </div>
</div>
<div id="page-content" class="row">
    <div class="small-12 medium-9 large-6 medium-centered columns">
        <?php $form = ActiveForm::begin([ 'id' => 'request-password-reset-form' ]); ?>

        <h2 class="form-title"><?= \Yii::t('app', 'Please fill out your email. A link to reset password will be sent there'); ?>:</h2>

        <?= $form->field($model, 'email', [
            'labelOptions' => [ 'disabled' => true, ],
            'inputOptions' => [
                'placeholder' => \Yii::t('app', 'Email'),
            ],
        ])->label(false) ?>
        <!-- Submit -->
        <div class="form-group text-right">
            <?= Html::submitButton(\Yii::t('app', 'Request Password Reset'), [ 'class' => 'btn btn-primary', 'name' => 'login-button' ]) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
