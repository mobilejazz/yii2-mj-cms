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
        <h5 class="form-title">You have successfully changed your password</h5>
    </div>
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