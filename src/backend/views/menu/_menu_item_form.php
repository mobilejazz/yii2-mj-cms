<?php

use mobilejazz\yii2\cms\backend\widgets\Submitter;
use mobilejazz\yii2\cms\common\models\ContentSource;
use mobilejazz\yii2\cms\common\models\MenuItem;
use mobilejazz\yii2\cms\common\models\MenuItemTranslation;
use mobilejazz\yii2\cms\common\widgets\BoxPanel;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View        $this
 * @var MenuItemTranslation $translation
 * @var MenuItem            $model
 * @var ActiveForm          $form
 */
if (!Yii::$app->request->isAjax)
{
    BoxPanel::begin([
        'title' => Yii::t('backend', 'Add Item to Menu'),
    ]);
}
$var  = \Yii::$app->security->generateRandomString(2);
$form = ActiveForm::begin([
    'id' => "menu-item-form-$var",
]);

echo $form->errorSummary($model) ?>

<?= $form->field($translation, 'title')
         ->textInput()
         ->label(Yii::t('backend', 'Title')); ?>

<div class="form-group">
    <label>Link</label>

    <div class="radio">
        <label>
            <input type="radio" name="menu-item-link"
                   value="content" <?= ($model->isNewRecord || $model->content_id != 0) ? "checked=\"\"" : "" ?>> <?= Yii::t('backend',
                'Link to Content') ?>
        </label>
    </div>
    <div class="radio">
        <label>
            <input type="radio" name="menu-item-link"
                   value="custom" <?= (!$model->isNewRecord && $model->content_id == 0) ? "checked=\"\"" : "" ?>><?= Yii::t('backend',
                'Custom URL') ?>
        </label>
    </div>

</div>

<div id="select2-menu-item-container">

    <?= $form->field($model, 'content_id')
             ->widget(Select2::className(), [
                 'data'          => ContentSource::getAsDropDownData(),
                 'options'       => [
                     'placeholder' => Yii::t('backend', 'Select a content...'),
                     'title'       => '',
                 ],
                 'pluginOptions' => [
                     'allowClear' => true,
                 ],
             ])
             ->label(false) ?>
</div>

<div id="menu-item-translation-link-container">
    <?= $form->field($translation, 'link')
             ->textInput()
             ->label(false); ?>
</div>

<!-- PARENT IN CASE THERE SHOULD BE ONE -->
<?= $form->field($model, 'parent')
         ->dropDownList($model->getPossibleParents())
         ->hint(Yii::t('backend', 'Does this item have a parent?')) ?>

<!-- CSS CLASS -->
<?= $form->field($model, 'class')
         ->textInput()
         ->hint(Yii::t('backend', 'CSS class')) ?>

<!-- TARGET -->
<?= $form->field($model, 'target')
         ->checkbox()
         ->label(Yii::t('backend', 'Open in new window?')) ?>

<?php if (!$model->isNewRecord): ?>
    <?= Submitter::widget([
        'model'         => $model,
        'displayDelete' => false,
        'cancelType'    => 'modal',
        'returnUrl'     => '/admin/user',
    ]) ?>
<?php endif ?>

<?php if ($model->isNewRecord): ?>
    <div class="form-group">
        <?= Html::a('<span class="fa fa-caret-left icon-margin small"></span> ' . Yii::t('backend', 'Add to Menu'),
            [ 'menu-item-create', 'menu_id' => $model->menu_id ], [
                'class' => 'btn btn-success',
                'data'  => [
                    'method' => 'post',
                ],
            ]); ?>
    </div>
<?php endif ?>
<?php ActiveForm::end();

if (!Yii::$app->request->isAjax)
{
    BoxPanel::end();
}

$script = <<< JS
// MENU ITEM TYPE SELECTION
var menu_form = $('#menu-item-form-$var');

// MENU ITEM TYPE SELECTION
var content_id_value = menu_form.find('#menuitem-content_id');
var translation_value = menu_form.find('#menuitemtranslation-link');
content_id_value.change(function () {
    translation_value.val('');
});
translation_value.change(function () {
    content_id_value.val('');
});

menuItemLinkOptions();
menu_form.find("input[name=menu-item-link]").on('change', menuItemLinkOptions);

function menuItemLinkOptions() {
    menu_form.find('#menuitem-content_id').show();
    var selected;
    var select2_container = menu_form.find('#select2-menu-item-container');
    var translation_container = menu_form.find('#menu-item-translation-link-container');
    selected = menu_form.find("input[name=menu-item-link]:checked").val();
    if (selected === 'content') {
        select2_container.show();
        translation_container.hide();
    } else {
        select2_container.hide();
        translation_container.show();
    }
};
JS;
$this->registerJs($script);

?>

