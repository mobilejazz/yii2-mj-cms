<?php

use mobilejazz\yii2\cms\backend\widgets\Submitter;
use mobilejazz\yii2\cms\common\widgets\BoxPanel;
use yii\bootstrap\ActiveForm;

/**
 * @var yii\web\View           $this
 * @var mobilejazz\yii2\cms\common\models\Menu     $model
 * @var yii\widgets\ActiveForm $form
 */

?>

<div class="col-xs-12 col-sm-3">
    <?php BoxPanel::begin([
        'title' => Yii::t('backend', 'Menu Publishing'),
    ]) ?>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'key')
             ->textInput([ 'maxlength' => true, 'placeholder' => Yii::t('backend', 'Menu key') ])
             ->label(Yii::t('backend', 'Key')) ?>


    <?= $form->field($model, 'class')
             ->textInput([ 'maxlength' => true ])
             ->label(Yii::t('backend', 'CSS Class')) ?>

    <?php echo $form->errorSummary($model); ?>

    <?= Submitter::widget([
        'model'         => $model,
        'returnUrl'     => '/admin/menu',
        'displayCancel' => false,
    ]) ?>

    <?php ActiveForm::end(); ?>
    <?php BoxPanel::end() ?>
</div>