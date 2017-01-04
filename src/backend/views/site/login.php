<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model mobilejazz\yii2\cms\common\models\LoginForm */

$this->title                     = 'Login';
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="login-box">
    <div class="login-logo">
        <?= Html::encode($this->title) ?>
    </div>
    <div class="header"></div>

    <div class="login-box-body">

        <div class="body">
            <?php $form = ActiveForm::begin([ 'id' => 'login-form' ]); ?>
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'password')
                     ->passwordInput() ?>
            <?= $form->field($model, 'rememberMe')
                     ->checkbox([ 'class' => 'simple' ]) ?>
        </div>
        <?php echo Html::submitButton(Yii::t('backend', 'Sign me in'), [
            'class' => 'btn btn-primary btn-flat btn-block',
            'name'  => 'login-button',
        ]) ?>
        <?php echo Html::a(Yii::t('backend', 'Forgot Password?'), [ 'request-password-reset' ], [
            'class' => 'btn btn-danger btn-flat btn-block',
            'name'  => 'login-button',
        ]) ?>
        <?php ActiveForm::end(); ?>

    </div>
</div>
