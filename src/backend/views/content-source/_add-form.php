<?php

use mobilejazz\yii2\cms\backend\widgets\Submitter;
use mobilejazz\yii2\cms\common\models\ComponentField;
use mobilejazz\yii2\cms\common\models\ContentComponent;
use mobilejazz\yii2\cms\common\models\ContentSource;
use mobilejazz\yii2\cms\common\models\WebForm;
use mobilejazz\yii2\cms\common\widgets\BoxPanel;
use yii\bootstrap\ActiveForm;

/**
 * @var yii\web\View           $this
 * @var string                 $lang
 * @var ContentSource          $model
 * @var ContentComponent       $component
 * @var ComponentField         $field
 * @var yii\widgets\ActiveForm $form
 */
if (!Yii::$app->request->isAjax)
{
    BoxPanel::begin([
        'title' => Yii::t('backend', 'Add new Form'),
    ]);
}

?>
<?php $form = ActiveForm::begin([
    'options' => [
        'class' => 'add-form-form',
    ],
]); ?>

<!-- DISPLAY ERRORS -->
<?php echo $form->errorSummary($model) ?>

<?= $form->field($field, "text")
         ->dropDownList(WebForm::asMap())
         ->label(Yii::t('backend', 'Choose one of your existing Forms.')); ?>

<?= Submitter::widget([
    'model'         => $field,
    'displayDelete' => false,
    'cancelType'    => 'modal',
]) ?>

<?php ActiveForm::end(); ?>

<?php
if (!Yii::$app->request->isAjax)
{
    BoxPanel::end();
}
$script = <<< JS
// ADD FORM TO CONTENT THROUGH AJAX.
$('body').off('submit', '.add-form-form');
$('body').on('submit', '.add-form-form', function (event) {
    event.preventDefault();
    // Dissallow the Text areas.
    window.destroyCKE();
    showLoader(true);
    showContentLoaderActivator(true);
    $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function (data) {
                window.refreshContentUi(data.id, data.msg, data.opened_boxes);
            }
        })
        .fail(function (response) {
            window.reloadAllHandlers;
            toastr.error(response);
        });
    return false;
});
JS;
$this->registerJs($script);

?>

