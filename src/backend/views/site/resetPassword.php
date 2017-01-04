<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var $this  yii\web\View
 * @var $form  yii\bootstrap\ActiveForm
 * @var $model mobilejazz\yii2\cms\backend\models\ResetPasswordForm
 */

$this->title                     = \Yii::t('app', 'Password reset');
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="login-box">
    <div class="login-logo">
        <?= Html::encode($this->title) ?>
        <h5 class="form-title"><?= \Yii::t('app', 'Please choose your new password'); ?>:</h5>
    </div>
    <div class="login-box-body">
        <!-- Page title -->
        <div class="body">
            <?php $form = ActiveForm::begin([ 'id' => 'password-reset-form' ]); ?>
            <?= $form->field($model, 'password', [
                'inputOptions' => [
                    'placeholder' => \Yii::t('app', 'New Password'),
                    'style'       => [
                        'width' => '100%',
                    ],
                ],
            ])
                     ->passwordInput() ?>
        </div>
        <?php echo Html::submitButton('Reset Password', [
            'class' => 'btn btn-danger btn-flat btn-block',
        ]) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>