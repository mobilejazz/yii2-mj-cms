<?php

use mobilejazz\yii2\cms\backend\widgets\Submitter;
use mobilejazz\yii2\cms\common\models\Components;
use mobilejazz\yii2\cms\common\models\ContentComponent;
use mobilejazz\yii2\cms\common\models\ContentSource;
use mobilejazz\yii2\cms\common\widgets\BoxPanel;
use yii\bootstrap\ActiveForm;

/**
 * @var yii\web\View     $this
 * @var string           $lang
 * @var ContentSource    $model
 * @var ContentComponent $component
 */
if (!Yii::$app->request->isAjax)
{
    BoxPanel::begin([
        'title' => Yii::t('backend', 'Add new Component'),
    ]);
}

?>
<?php $form = ActiveForm::begin([
    'options' => [
        'class' => 'add-component-form',
    ],
]);

// Display Errors
echo $form->errorSummary($model);

// Get the Components available and remove the form components from this action.
$map = Components::asMap();
unset($map[ 'form' ]);

echo $form->field($component, 'type')
          ->dropDownList($map);

echo Submitter::widget([
    'model'         => $component,
    'displayDelete' => false,
    'cancelType'    => 'modal',
]);

ActiveForm::end(); ?>

<?php
if (!Yii::$app->request->isAjax)
{
    BoxPanel::end();
}

$content_id = $model->id;
$script     = <<< JS
// ADD COMPONENT THROUGH AJAX.
$('body').off('submit', ".add-component-form");
$('body').on('submit', '.add-component-form', function (event) {
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

