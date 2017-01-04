<?php

use mobilejazz\yii2\cms\backend\modules\filemanager\assets\FilemanagerAsset;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var $this  yii\web\View
 * @var $model filemanager\models\Mediafile
 * @var $form  yii\widgets\ActiveForm
 */

$bundle = FilemanagerAsset::register($this);
echo Html::img(Yii::getAlias('@web') . $model->getDefaultThumbUrl($bundle->baseUrl)) ?>
<ul class="detail">
    <li><?= $model->type ?></li>
    <li><?= Yii::$app->formatter->asDatetime($model->getLastChanges()) ?></li>
    <?php if ($model->isImage()) : ?>
        <li><?= $model->getOriginalImageSize($this->context->module->routes) ?></li>
    <?php endif; ?>
    <li><?= $model->getFileSize() ?></li>
    <?= Html::button('Copy URL', [
        'class'                 => 'btn btn-xs btn-primary',
        'id'                    => 'copy-button',
        'data-clipboard-target' => '#link-url',
    ]); ?>
    <li><?= Html::a(\Yii::t('backend', 'Delete'), 'delete?id=' . $model->id, [
            'class'        => 'link link-danger',
            'confirmation' => Yii::t('backend', 'Are you sure you want to delete this upload?'),
            'role'         => 'delete',
            'data-id'      => $model->id,
        ]) ?></li>
</ul>
<div class="filename"><?= \Yii::t('backend', 'File Name'); ?>: <?= $model->filename ?></div>
<div id="link-url" style="white-space: nowrap; width: 20em; overflow: hidden; text-overflow: ellipsis;"><?= $model->url ?></div>
<?php $form = ActiveForm::begin([
    'action'  => [ 'default/update', 'id' => $model->id ],
    'options' => [ 'id' => 'control-form' ],
]); ?>
<?php if ($model->isImage()) : ?>
    <?= $form->field($model, 'alt')
             ->textInput([ 'class' => 'form-control input-sm' ]); ?>
<?php endif; ?>

<?= $form->field($model, 'description')
         ->textarea([ 'class' => 'form-control input-sm' ]); ?>

<?php if ($model->isImage()) : ?>
    <div class="form-group<?= $strictThumb ? ' hidden' : '' ?>">
        <?= Html::label(Yii::t('backend', 'Select image size'), 'image', [ 'class' => 'control-label' ]) ?>

        <?= Html::dropDownList('url', $model->getThumbUrl($strictThumb), $model->getImagesList($this->context->module), [
            'class' => 'form-control input-sm',
        ]) ?>
        <div class="help-block"></div>
    </div>
<?php else : ?>
    <?= Html::hiddenInput('url', $model->url) ?>
<?php endif; ?>

<?= Html::hiddenInput('id', $model->id) ?>

<?= Html::button('<span class="fa fa-check icon-margin small"></span> ' . Yii::t('backend', 'Insert'),
    [ 'id' => 'insert-btn', 'class' => 'btn btn-primary' ]) ?>

<?= Html::submitButton(Yii::t('backend', 'Save'), [ 'class' => 'btn btn-success' ]) ?>

<?php if ($message = Yii::$app->session->getFlash('mediafileUpdateResult')) : ?>
    <div class="text-success"><?= $message ?></div>
<?php endif; ?>
<?php ActiveForm::end(); ?>
<script>new Clipboard('#copy-button');</script>