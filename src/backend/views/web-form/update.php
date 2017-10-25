<?php
/**
 * @var yii\web\View                                    $this
 * @var mobilejazz\yii2\cms\common\models\WebFormDetail $details
 * @var mobilejazz\yii2\cms\common\models\WebFormRow[]  $rows
 * @var mobilejazz\yii2\cms\common\models\WebForm       $model
 * @var array                                           $field_errors
 */
use mobilejazz\yii2\cms\common\models\Fields;
use mobilejazz\yii2\cms\common\models\WebFormRow;
use mobilejazz\yii2\cms\common\models\WebFormRowField;
use mobilejazz\yii2\cms\common\widgets\BoxPanel;
use trntv\aceeditor\AceEditor;
use unclead\multipleinput\MultipleInput;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

$order = !empty($rows) ? WebFormRow::getMaxOrder($rows[ 0 ]) : null;

$this->title                     = $model->isNewRecord ? Yii::t('backend', 'Add New Form') : Yii::t('backend',
        'Web Form') . ': ' . $model->getTitle();
$this->params[ 'breadcrumbs' ][] = [
    'label' => Yii::t('backend', 'Web Form'),
    'url'   => [ 'index' ],
];
$this->params[ 'breadcrumbs' ][] = [
    'label' => (string) $model->getTitle(),
    'url'   => [
        'update',
        'id' => $model->id,
    ],
];
$this->params[ 'breadcrumbs' ][] = Yii::t('backend', 'Edit');
$form                            = ActiveForm::begin();
?>
<!-- Content -->
<div class="row">
    <div class="col-xs-12 col-sm-9">
        <?php
        // ============================
        // WEB FORM RELATED
        // ============================
        BoxPanel::begin([
            'title' => Yii::t('backend', 'Form Details'),
        ]) ?>
        <?php if (!$model->isNewRecord): ?>
            <div class="form-group form-inline">
                <?= Html::a("<i class=\"fa fa-plus icon-margin small\"></i> " . Yii::t('backend', 'Add New'), 'create',
                    [ 'class' => 'btn btn-primary' ]) ?>
            </div>
        <?php endif ?>

        <?php

        echo $form->field($details, 'title')
                  ->textInput([ 'maxlength' => true, 'placeholder' => Yii::t('backend', 'The form title the users will see') ])
                  ->label(Yii::t('backend', 'Title'));

        echo $form->field($details, 'emails')
                  ->widget(MultipleInput::className(), [
                      'max'             => 6,
                      'allowEmptyList'    => false,
                      'enableGuessTitle'  => true,
                      'addButtonPosition' => MultipleInput::POS_ROW,
                  ])
                  ->label(false)
                  ->hint(Yii::t('backend', 'Where do you want the results sent?'));

        echo $form->field($details, 'css_class')
                  ->textInput([ 'maxlength' => true, 'placeholder' => Yii::t('backend', 'Custom CSS class to include within the <form> element') ]);

        echo $form->field($details, 'send_mail')
                  ->checkbox();

        echo $form->field($details, 'description')
                  ->textarea([
                      'maxlength'   => true,
                      'placeholder' => Yii::t('backend', 'You can set a description for the form if you wish to.'),
                      'style'       => 'min-width: 300px',
                  ])
                  ->label(Yii::t('backend', 'Form description'));

        echo $form->field($details, 'message')
                  ->textarea([
                      'maxlength'   => true,
                      'placeholder' => Yii::t('backend', 'You can set a thank you message for the form if you wish to.'),
                      'style'       => 'min-width: 300px',
                  ])
                  ->label(Yii::t('backend', 'Thank you Message'));

        echo $form->field($details, 'script')
                  ->widget(AceEditor::className(), [
                      'mode'    => 'javascript',
                      'theme'   => 'github',
                      'options' => [
                          'class' => 'ace-editor',
                      ],
                  ]);

        BoxPanel::end();

        // ============================
        // WEB FORM RELATED
        // ============================
        if (!$model->isNewRecord)
        {
            echo "<h4>" . Yii::t('backend', 'Form Rows') . "</h4>";

            echo "<p>" . Yii::t('backend', 'Please add or modify the rows that you want in the web form.') . "</p>";

            if (isset($field_errors) && $field_errors != null && count($field_errors) > 1)
            {
                ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-ban"></i> <?= Yii::t('backend', 'Alert!') ?></h4>
                    <?= Yii::t('backend', 'Attention, some rows/fields have errors and have not been saved. Please review them.') ?>
                </div>
                <?php
            }

            echo "<div class='sortable-list'>";
            /**
             * @var Int          $index
             * @var WebFormRow[] $rows
             */
            foreach ($rows as $key => $row)
            {
                $row_title = $row->legend ? $row->legend : Yii::t('backend', 'Form Row number {number, spellout}', [ 'number' => $row->order, ]);
                BoxPanel::begin([
                    'title'    => $row_title,
                    'open'     => $key == 0,
                    'sortable' => true,
                    'options'  => [
                        'id' => $row->id,
                    ],
                ]);

                echo "<div class='row'>";

                echo $form->field($row, "[$key]legend", [ 'options' => [ 'class' => 'col-md-6', ] ])
                          ->textInput()
                          ->hint(Yii::t('backend', 'You can set a legend for this row if you wish to, if not, leave empty'));

                echo $form->field($row, "[$key]internal_name", [ 'options' => [ 'class' => 'col-md-6', ] ])
                    ->textInput()
                    ->hint(Yii::t('backend', 'You can set a internal name for this row if you wish to, if not, leave empty. This internal name never will be show on the website.'));

                echo "</div>";

                /**
                 *  GO THROUGH THE FIELDS IN THE ORDER THEY SHOULD BE DISPLAYED.
                 * @var Int             $index
                 * @var WebFormRowField $field
                 */
                foreach ($row->getOrderedWebFormRowFields(Yii::$app->language) as $index => $field)
                {
                    if ($field->web_form_row != $row->id)
                    {
                        continue;
                    }
                    // Start a well to distinguish between Fields
                    echo "<div class='well well-sm'>";

                    echo "<h4>" . Yii::t('backend', 'Field in the {n, spellout,%spellout-ordinal} position', [ 'n' => $field->order, ]) . "</h4>";
                    // Add the found errors here.
                    if (isset($field_errors) && $field_errors != null && isset($field_errors[ $field->id ]) && $field_errors[ $field->id ] != null)
                    {
                        $field->addErrors($field_errors[ $field->id ]);
                    }

                    // UNSET FORM DROPDOWN.
                    $map = Fields::asMap();

                    echo "<div class='row'>";
                    // Name
                    echo $form->field($field, "[$field->id]name", [ 'options' => [ 'class' => 'col-md-6', ] ])
                              ->textInput()
                              ->hint(Yii::t('backend', 'The name of the field'));

                    unset($map[ 'form-dropdown' ]);
                    // Web Form Field Type
                    echo $form->field($field, "[$field->id]type", [ 'options' => [ 'class' => 'col-md-6', ], ])
                              ->dropDownList($map)
                              ->hint(Yii::t('backend', 'What type of Input is expected from the User?'));

                    echo "</div><div class='row'>";

                    // PlaceHolder
                    echo $form->field($field, "[$field->id]placeholder", [ 'options' => [ 'class' => 'col-md-3', ] ])
                              ->textInput()
                              ->hint(Yii::t('backend', 'Any Placeholder for this field?'));

                    // Hint
                    echo $form->field($field, "[$field->id]hint", [ 'options' => [ 'class' => 'col-md-3' ] ])
                              ->textInput()
                              ->hint(Yii::t('backend', 'Hint for the user to know what you expect them to input'));

                    // Required field?
                    echo $form->field($field, "[$field->id]required", [ 'options' => [ 'class' => 'col-md-3', ], ])
                              ->dropDownList([
                                  0 => Yii::t('backend', 'No'),
                                  1 => Yii::t('backend', 'Yes'),
                              ])
                              ->hint(Yii::t('backend', 'Is this field going to be required by the user to input?'));

                    // Required field?
                    echo $form->field($field, "[$field->id]is_sensitive", [ 'options' => [ 'class' => 'col-md-3', ], ])
                              ->dropDownList([
                                  0 => Yii::t('backend', 'No'),
                                  1 => Yii::t('backend', 'Yes'),
                              ])
                              ->hint(Yii::t('backend', 'Should this field be saved in the database?'));

                    // Error Message
                    echo $form->field($field, "[$field->id]error_message", [ 'options' => [ 'class' => 'col-sm-12' ] ])
                              ->textInput()
                              ->hint(Yii::t('backend', 'You can define a custom error message for this field'));

                    echo "</div>";

                    // Delete a Field
                    echo Html::a('<span class="fa fa-trash icon-margin small"></span> ' . Yii::t('backend', 'Delete Field'),
                        [ 'field-delete', 'id' => $field->id ], [
                            'class' => 'btn  btn-xs btn-danger',
                            'data'  => [
                                'confirm' => Yii::t('backend', 'Are you sure?'),
                                'method'  => 'post',
                            ],
                            'style' => 'margin-left: 5px;',
                        ]);

                    echo "<div class=\"clearfix\"></div>";

                    echo "</div>";
                }

                echo "<div class=\"clearfix\"></div>";

                // Add new Field to this Row.
                echo Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('backend', 'Add Field to Row'), Url::to([
                    'add-field',
                    'id'    => $row->id,
                    'order' => WebFormRowField::getMaxOrder($row),
                ]), [
                    'class' => 'btn btn-primary',
                ]);

                // Delete a Row
                echo Html::a('<span class="fa fa-trash icon-margin small"></span> ' . Yii::t('backend', 'Delete Row'),
                    [ 'row-delete', 'id' => $row->id ], [
                        'class' => 'btn  btn-sm btn-danger pull-right',
                        'data'  => [
                            'confirm' => Yii::t('backend', 'Are you sure?'),
                            'method'  => 'post',
                        ],
                        'style' => 'margin-left: 5px;',
                    ]);

                BoxPanel::end();
            }

            echo "</div>";

            echo Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('backend', 'Add new Row'), Url::to([
                'add-row',
                'id'    => $model->id,
                'order' => $order,
            ]), [
                'class' => 'btn btn-primary',
            ]);
            echo "<hr>";
        }
        ?>

        <?php if ($model->isNewRecord): ?>
            <?= Html::a('<span class="glyphicon glyphicon-check"></span> ' . Yii::t('backend', 'Create'), [ 'create', 'id' => $model->id ], [
                'class' => 'btn btn-success',
                'data'  => [
                    'method' => 'post',
                ],
            ]); ?>
        <?php endif ?>
        <?php ActiveForm::end(); ?>
    </div>

    <?php if (!$model->isNewRecord): ?>
        <div class="col-xs-12 col-sm-3 affix-element">
            <?php BoxPanel::begin([
                'title' => Yii::t('backend', 'Actions'),
            ]) ?>
            <div class="form-inline add-top-margin">
                <?= Html::a('<span class="fa fa-check icon-margin small"></span> ' . Yii::t('backend', 'Save'), [ 'update', 'id' => $model->id ], [
                    'class' => 'btn btn-success',
                    'data'  => [
                        'method' => 'post',
                    ],
                ]); ?>

                <?= Html::a('<span class="fa fa-trash icon-margin small"></span> ' . Yii::t('backend', 'Delete'), [ 'delete', 'id' => $model->id ], [
                    'class' => 'btn btn-danger',
                    'data'  => [

                        'method' => 'post',
                    ],
                ]); ?>
            </div>
            <?php BoxPanel::end() ?>
        </div>
    <?php endif ?>
</div>

