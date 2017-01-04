<?php

use mobilejazz\yii2\cms\backend\widgets\Submitter;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model mobilejazz\yii2\cms\backend\modules\i18n\models\I18nMessage */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="i18n-message-form">

    <?php $form = ActiveForm::begin(); ?>

    <p><?= Yii::t('backend', 'You are translating the Text: ') ?></p>
    <blockquote>
        <?= $model->sourceMessage ?>
    </blockquote>

    <?php echo $form->field($model, 'translation')
                    ->textarea([ 'rows' => 6 ])
                    ->label(Yii::t('backend', 'Translation in') . ' ' . \mobilejazz\yii2\cms\common\models\Locale::getCurrent()) ?>


    <?= Submitter::widget([
        'model'         => $model,
        'displayDelete' => false,
        'cancelType'    => 'modal',
        'returnUrl'     => '/admin/i18n/i18n-message',
    ]) ?>

    <?php ActiveForm::end(); ?>

</div>
