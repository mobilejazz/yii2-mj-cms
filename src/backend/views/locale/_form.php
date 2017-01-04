<?php

use mobilejazz\yii2\cms\backend\widgets\Submitter;
use mobilejazz\yii2\cms\common\models\Locale;
use yii\bootstrap\ActiveForm;

/**
 * @var yii\web\View           $this
 * @var mobilejazz\yii2\cms\common\models\Locale   $model
 * @var yii\widgets\ActiveForm $form
 */

?>

<div class="locale-form">

    <?php $form = ActiveForm::begin(); ?>
    <h3>Attention:</h3>
    When creating / updating new languages keep in mind that many things will happen.
    <ul>
        <li>Only one default language can be defined. It will be the default language for users on the website.</li>
        <li>When deleating a language, everything defined in that language will be removed.</li>
        <li>When creating a new language many placeholders will be created for many things such as content / links and redirects / menu items
            and so on... please take a look at them.
        </li>
        <li>Remember that whatever you place here will ALSO be used for slugs in links, for example 'en' will be used for English links:
            /en/about-us will render the contents in english.
        </li>
    </ul>
    <p>
        <!-- LANG. If we are modifying the english language, disable changes. -->
        <?= $form->field($model, 'lang')
                 ->textInput([ 'maxlength' => true, 'disabled' => ($model->lang == 'en' || !$model->isNewRecord) ])
                 ->label(Yii::t('backend', 'Laguage Identifier')) ?>

        <!-- IDENTIFICATION -->
        <?= $form->field($model, 'label')
                 ->textInput([ 'maxlength' => true ])
                 ->label(\Yii::t('backend', 'Language Name'))
                 ->hint(Yii::t('backend', 'This will be shown on menus and so on.')) ?>

        <!-- COUNTRY CODE -->
        <?= $form->field($model, 'country_code')
                 ->textInput([ 'maxlength' => true ])
                 ->label(\Yii::t('backend', 'Country Code'))
                 ->hint(Yii::t('backend', 'The country code identifier (for the flags).')) ?>

        <?php if (!$model->isNewRecord && !$model->default): ?>
            <?= $form->field($model, 'default')
                     ->checkbox()
                     ->label(Yii::t('backend', 'Default language?')) ?>
        <?php endif ?>

        <?php if ($model->isNewRecord): ?>
            <?= $form->field($model, 'base_lang')
                     ->dropDownList(Locale::getAllLocalesAsMap(true))
                     ->label(\Yii::t('backend', 'Language to pick as a base'))
                     ->hint(\Yii::t('backend',
                         'This language will be used as a base to duplicate all the content / slugs / web forms and so on...')) ?>
        <?php endif; ?>

        <!-- DEFAULT LANGUAGE? -->
        <?php if (!$model->default): ?>
            <!-- IS THIS LANGUAGE USED IN THE WEBSITE? -->
            <?= $form->field($model, 'used')
                     ->checkbox()
                     ->label(Yii::t('backend', 'Is this language used in the website?')) ?>
        <?php endif ?>

        <?= $form->field($model, 'rtl')
                 ->checkbox()
                 ->label(Yii::t('backend', 'Right to Left Language?')) ?>


    </p>
    <hr/>
    <?php echo $form->errorSummary($model); ?>

    <?= Submitter::widget([
        'model'         => $model,
        'returnUrl'     => '/admin/locale',
        'displayDelete' => false,
    ]) ?>
    <?php ActiveForm::end(); ?>


</div>