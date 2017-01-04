<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View                            $this
 * @var mobilejazz\yii2\cms\backend\models\search\UrlRedirectSearch $model
 * @var yii\widgets\ActiveForm                  $form
 */
?>

<div class="url-redirect-search">

    <?php $form = ActiveForm::begin([
        'action' => [ 'index' ],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'origin_slug') ?>

    <?= $form->field($model, 'destination_slug') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', [ 'class' => 'btn btn-primary' ]) ?>
        <?= Html::resetButton('Reset', [ 'class' => 'btn btn-primary' ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
