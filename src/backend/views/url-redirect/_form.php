<?php

use mobilejazz\yii2\cms\backend\widgets\Submitter;
use yii\bootstrap\ActiveForm;

/**
 * @var yii\web\View              $this
 * @var mobilejazz\yii2\cms\common\models\UrlRedirect $model
 * @var yii\widgets\ActiveForm    $form
 */

?>
    <div class="url-redirect-form">
        <?php if (Yii::$app->session->hasFlash('url_redirect_error'))
        {
            ?>
            <div class="alert alert-error">
                <?= Yii::$app->session->getFlash('url_redirect_error'); ?>
            </div>
            <?php
        } ?>
    </div>

<?php $form = ActiveForm::begin(); ?>
    <p>
        <?= $form->field($model, 'origin_slug')
                 ->textInput([ 'maxlength' => true ]) ?>
        <?= $form->field($model, 'destination_slug')
                 ->textInput([ 'maxlength' => true ]) ?>
    </p>
    <hr/>
<?= $form->errorSummary($model); ?>
<?= Submitter::widget([
    'model'         => $model,
    'displayDelete' => false,
    'cancelType'    => 'modal',
    'returnUrl'     => '/admin/url-redirec',
]) ?>

<?php ActiveForm::end(); ?>