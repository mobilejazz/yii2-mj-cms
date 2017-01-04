<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var $this  yii\web\View
 * @var $form  yii\bootstrap\ActiveForm
 * @var $model mobilejazz\yii2\cms\backend\models\PasswordResetRequestForm
 */

$this->title                     = \Yii::t('app', 'Request password reset');
$this->params[ 'breadcrumbs' ][] = $this->title;
?>

<div class="login-box">
    <div class="login-logo">
        <?= Html::encode($this->title) ?>
        <h5 class="form-title"><?= \Yii::t('app', 'Please fill out your email. A link to reset password will be sent there'); ?>:</h5>
    </div>
    <div class="login-box-body">
        <!-- Page title -->
        <div class="body">
            <?php $form = ActiveForm::begin([ 'id' => 'request-password-reset-form' ]); ?>
            <?= $form->field($model, 'email', [
                'inputOptions' => [
                    'placeholder' => \Yii::t('app', 'Email'),
                    'style'       => [
                        'width' => '100%',
                    ],
                ],
            ]) ?>
        </div>
        <?php echo Html::submitButton('Request Password Reset', [
            'class' => 'btn btn-danger btn-flat btn-block',
        ]) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>