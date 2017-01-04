<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View                       $this
 * @var mobilejazz\yii2\cms\backend\models\search\LocaleSearch $model
 * @var yii\widgets\ActiveForm             $form
 */
?>

<div class="locale-search">

    <?php $form = ActiveForm::begin([
        'action' => [ 'index' ],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'lang') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Search'), [ 'class' => 'btn btn-primary' ]) ?>
        <?= Html::resetButton(Yii::t('backend', 'Reset'), [ 'class' => 'btn btn-default' ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
