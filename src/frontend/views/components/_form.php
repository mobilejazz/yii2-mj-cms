<?php
use mobilejazz\yii2\cms\common\models\ContentComponent;
use mobilejazz\yii2\cms\common\models\Fields;
use mobilejazz\yii2\cms\common\models\WebForm;
use mobilejazz\yii2\cms\common\models\WebFormDetail;
use mobilejazz\yii2\cms\common\models\WebFormRow;
use mobilejazz\yii2\cms\common\models\WebFormRowField;
use mobilejazz\yii2\cms\frontend\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * This file is the one that has to render the fields for the
 * WebForms that have been created from the backend.
 *
 *
 * Some of the computing will have to happen here as we
 * do not have a controller taking care of rendering of components.
 *
 * @var yii\web\View     $this
 * @var int              $size
 * @var ContentComponent $component
 */
// GET the Form ID.
$form_id       = intval($component->componentFields[ 0 ]->text);
$parsed_fields = Yii::$app->session->get('parsed_fields');
if (!isset($form_id) || is_null($form_id))
{
    throw new NotFoundHttpException(Yii::t('app', 'It looks like the Form you are trying to access can not be found.'));
}
/** @var WebForm $web_form */
$web_form = WebForm::findOne([ 'id' => $form_id ]);
/** @var WebFormDetail $web_form_detail */
$web_form_detail = $web_form->getCurrentDetails(\Yii::$app->language);
if (!isset($web_form) || is_null($web_form))
{
    throw new NotFoundHttpException(Yii::t('app', 'It looks like the Form you are trying to access can not be found.'));
}
/** @var WebFormRow[] $rows */
$rows = $web_form->getOrderedWebFormRows(Yii::$app->language);
?>
    <!-- Form description -->
<?php if (isset($web_form_detail->description) && strlen($web_form_detail->description) > 1)
{ ?>
    <div class="form-description text-with-heading">
        <div class="row">
            <div class="small-12 medium-10 medium-offset-1 columns">
                <?= $web_form_detail->description ?>
            </div>
        </div>
    </div>
<?php } ?>
    <!-- Form -->
    <div class="form-component">
        <div class="row">
            <div class="small-12 medium-6 medium-offset-3 columns">
                <?php
                $custom_css = $web_form_detail->css_class;
                // Begin form
                $form = ActiveForm::begin([
                    'id'      => 'floating-label-form',
                    'action'  => Url::to([
                        '/submit',
                    ]),
                    'options' => [
                        'class' => 'js-form' . ' ' . $custom_css,
                    ]
                ]);
                // Fields
                foreach ($rows as $row)
                {
                    if ($row->hasLegend())
                    {
                        echo '<h2 class="form-title">' . $row->legend . '</h2>';
                    }
                    /** @var WebFormRowField[] $fields */
                    $fields = $row->getOrderedWebFormRowFields(Yii::$app->language);
                    $cols   = count($fields);
                    if ($cols > 0)
                    {
                        // CALCULATE THE GRID POSITIONING.
                        echo '<div class="row">';
                        $minSpan   = floor(12 / $cols);
                        $remainder = (12 % $cols);
                        foreach ($fields as $field)
                        {
                            $width = $minSpan;
                            if ($remainder > 0)
                            {
                                $width += 1;
                                $remainder--;
                            }
                            // Add the found errors here.
                            if (isset($parsed_fields) && $parsed_fields != null)
                            {
                                if (isset($parsed_fields[ $field->id ][ 'errors' ]) && $parsed_fields[ $field->id ][ 'errors' ] != null)
                                {
                                    $field->addErrors($parsed_fields[ $field->id ][ 'errors' ]);
                                }
                                if (isset($parsed_fields[ $field->id ][ 'value' ]) && $parsed_fields[ $field->id ][ 'value' ] != null)
                                {
                                    $field->text = $parsed_fields[ $field->id ][ 'value' ];
                                }
                            }
                            echo '<div class="small-12 medium-' . $width . ' columns">';
                            $widget = Fields::getWidget($form, $field, $field->name, $field->placeholder ?: $field->name, $field->hint ?: null, [
                                'options'      => [
                                    'class' => 'form-group',
                                ],
                                'inputOptions' => [
                                    'prepend' => '<label class="ie-only">' . $field->name . '</label>',
                                    'append'  => isset($field->hint) && strlen($field->hint) > 1 ? '<i class="fa fa-info-circle js-input-info"></i>' : null
                                ]
                            ]);
                            echo $widget;
                            echo '</div>';
                        }
                        echo '</div>';
                    }
                }
                // Submit
                echo Html::submitButton(Yii::t('app', 'Submit'), [ 'class' => 'button' ]);
                // End form
                ActiveForm::end();
                ?>
            </div>
        </div>
    </div>
<?php
// Clear errors and values
Yii::$app->session->remove('parsed_fields');