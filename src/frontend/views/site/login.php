<?php
use mobilejazz\yii2\cms\frontend\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model mobilejazz\yii2\cms\common\models\LoginForm */

$this->title                     = \Yii::t('app', 'Login');
$this->params[ 'breadcrumbs' ][] = $this->title;
?>

<div class="row">
    <div class="col-xs-12 col-sm-10 col-md-8 col-lg-6 col-sm-offset-1 col-md-offset-2 col-lg-offset-3">
        <div class="page-title">
            <h1><?= \Yii::t('app', 'LOGIN'); ?></h1>
        </div>

        <div class="page-content">
            <p><?= \Yii::t('app',
                    'Get access to meaningful content for your daily practice: online courses, webinars with physicians, health economics, resources & support... all in one place'); ?></p>
        </div>

        <div class="form-login">
            <?php $form = ActiveForm::begin([ 'id' => 'login-form' ]); ?>

            <?= $form->errorSummary($model); ?>

            <?= $form->field($model, 'email', [
                'labelOptions' => [ 'disabled' => true, ],
                'inputOptions' => [
                    'placeholder' => \Yii::t('app', 'Email'),
                    'class' => 'form-control'
                ],
            ])
                     ->label(false) ?>
            <?= $form->field($model, 'password', [
                'labelOptions' => [ 'disabled' => true, ],
                'inputOptions' => [
                    'placeholder' => \Yii::t('app', 'Password'),
                    'class' => 'form-control'
                ]
            ])
                     ->label(false)
                     ->passwordInput() ?>
            <?= $form->field($model, 'rememberMe')
                     ->checkbox([ 'label' => Yii::t('app', 'Remember Me?') ], true) ?>


            <!-- Submit -->
            <div class="form-group">
                <?= Html::submitButton(\Yii::t('app', 'Login'), [ 'class' => 'btn btn-default', 'name' => 'login-button' ]) ?>
            </div>

            <?php ActiveForm::end(); ?>

            <!-- fporm nav -->
            <div class="form-navigation">
                <p class="small"><a href="<?= Url::to(\Yii::t('url', '/request-password-reset')) ?>"><?= \Yii::t('app', 'Forgot your password?'); ?></a> | <a href="<?= Url::to(\Yii::t('url', '/signup')) ?>"><?= \Yii::t('app', 'Sign Up'); ?></a></p>
            </div>
        </div>
    </div>
</div>