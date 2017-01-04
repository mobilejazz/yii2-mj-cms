<?php
use yii\helpers\Html;

/**
 * @var $this  yii\web\View
 * @var $model mobilejazz\yii2\cms\backend\models\PasswordResetRequestForm
 */

$this->title                     = \Yii::t('app', 'Request password reset');
$this->params[ 'breadcrumbs' ][] = $this->title;
?>

<div class="login-box">
    <div class="login-logo">
        <?= Html::encode($this->title) ?>
        <h5 class="form-title">Your request for a password reset has been received. You should shortly receive an email with details of what to do
            next to change your password</div>
    <div class="login-box-body">
        <!-- Page title -->
        <div class="body">
            <?php echo Html::a('Return to Login', [ '/' ], [
                'class' => 'btn btn-primary btn-flat btn-block',
                'name'  => 'login-button',
            ]) ?>
        </div>
    </div>
</div>