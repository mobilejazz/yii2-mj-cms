<?php

/**
 * @var $components ContentComponent[]
 */
use mobilejazz\yii2\cms\common\models\ComponentField;
use mobilejazz\yii2\cms\common\models\ContentComponent;
use mobilejazz\yii2\cms\common\models\Fields;
use mobilejazz\yii2\cms\common\widgets\BoxPanel;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var int              $key
 * @var ContentComponent $component
 */
foreach ($components as $key => $component)
{
    // Save variables.
    $group          = ContentComponent::getGroup($component);
    $group_size     = count($group);
    $inGroup        = count($group) > 1;
    $first_in_group = false;
    $last_in_group  = false;

    $box_panel_open = $component->hasErrors() ? true : false;

    if ($inGroup)
    {
        $first_in_group = $component->isFirstInGroup();
        $last_in_group  = $component->isLastInGroup();
    }

    $sortable = (!$inGroup || $first_in_group) ? true : false;
    if ($first_in_group)
    {
        BoxPanel::begin([
            'title'    => Yii::t('backend', 'Component: ' . $component->title),
            'open'     => $box_panel_open,
            'type'     => 'primary',
            'sortable' => $sortable,
            'options'  => [
                'id' => $component->id,
            ],
        ]);

        if ($sortable)
        {
            $sortable = false;
        }
    }

    $fields = $component->getOrderedComponentFields(Yii::$app->language);

    BoxPanel::begin([
        'title'    => $component->title,
        'open'     => $box_panel_open,
        'sortable' => $sortable,
        'hidden'   => !$inGroup || count($fields) > 0 ? false : true,
        'options'  => [
            'id' => $component->id,
        ],
    ]);

    if ($component->displayTitle())
    {
        echo Fields::field(null, $component, "[$key]title")
                   ->textInput();
    }
    else
    {
        echo Fields::field(null, $component, "[$key]title")
                   ->textInput()
                   ->hiddenInput()
                   ->label(false);
    }

    /**
     * @var Int            $index
     * @var ComponentField $field
     */
    foreach ($fields as $index => $field)
    {
        $show_label = $field->isChildren() ? false : true;
        if ($field->hasChildren())
        {
            $show_label = false;
            BoxPanel::begin([
                'title' => Fields::getName($field->component->type, $field->type),
                'type'  => 'primary',
            ]);
        }

        // Add the found errors here.
        if (isset($field_errors) && $field_errors != null && isset($field_errors[ $field->id ]) && $field_errors[ $field->id ] != null)
        {
            $field->addErrors($field_errors[ $field->id ]);
        }
        // GO THROUGH THE FIELDS IN THE ORDER THEY SHOULD BE DISPLAYED.
        // RENDER THE WIDGET FOR THIS PARTICULAR FIELD - IMPORTANT
        $label = Fields::getName($component->type, $field->type);
        /** @var ActiveForm $f */
        $f = isset($form) && $form != null ? $form : null;
        echo Fields::getWidget($f, $field, $label, null, null);

        if ($field->isRepeatable() || $field->canBeDeleted())
        {
            echo "<div style='display: block; margin-bottom: 15px;'>";
        }
        if ($field->isRepeatable())
        {

            echo Html::a('<span class="fa fa-plus icon-margin small"></span> ' . Yii::t('backend',
                    'Add another') . ' ' . Fields::getName($field->component->type, $field->type),
                [ '/content-source/add-field', 'id' => $field->id ], [
                    'class' => 'btn-sm btn-primary add-field',
                ]);
        }

        if ($field->canBeDeleted())
        {
            echo Html::a('<span class="fa fa-trash icon-margin small"></span> ' . Yii::t('backend', 'Delete field'),
                [ '/content-source/delete-field', 'id' => $field->id ], [ 'class' => 'btn-sm btn-danger pull-right' ]);
        }
        if ($field->isRepeatable() || $field->canBeDeleted())
        {
            echo "</div>";
        }

        if ($field->isChildren() && $field->isLastChildren())
        {
            BoxPanel::end();
        }
    }

    if ($inGroup)
    {

        // Move Up
        echo Html::a('<span class="fa fa-caret-up small"></span> ', [ 'component-move-within-group-up', 'id' => $component->id ], [
            'class'       => 'btn btn-sm btn-default',
            'title'       => Yii::t('backend', 'Move Up within group'),
            'data-toggle' => 'tooltip',
        ]);

        // Move Down
        echo Html::a('<span class="fa fa-caret-down small"></span> ', [ 'component-move-within-group-down', 'id' => $component->id ], [
            'class'       => 'btn btn-sm  btn-default',
            'title'       => Yii::t('backend', 'Move Down within group'),
            'style'       => 'margin-left: 5px;',
            'data-toggle' => 'tooltip',
        ]);

        // Delete component
        echo Html::a('<span class="fa fa-trash icon-margin small"></span> ' . Yii::t('backend', 'Delete'),
            [ 'component-delete', 'id' => $component->id ], [
                'class' => 'btn  btn-sm btn-danger pull-right component-delete',
                'style' => 'margin-left: 5px;',
            ]);
    }

    echo "<div class=\"clearfix\"></div>";

    if (!$inGroup)
    {
        // Duplicate component
        if ($component->isRepeatable())
        {
            echo Html::a('<span class="fa fa-plus icon-margin small"></span> ' . Yii::t('backend', 'Duplicate'),
                [ 'component-duplicate', 'id' => $component->id ], [
                    'class' => 'btn btn-sm btn-primary component-duplicate',
                    'style' => 'margin-left: 5px;',
                ]);
        }
        // Delete component
        echo Html::a('<span class="fa fa-trash icon-margin small"></span> ' . Yii::t('backend', 'Delete'),
            [ 'component-delete', 'id' => $component->id ], [
                'class' => 'btn  btn-sm btn-danger pull-right component-delete',
                'style' => 'margin-left: 5px;',
            ]);
    }

    if (!$inGroup || count($components) > 1)
    {
        BoxPanel::end();
    }

    if ($last_in_group && $inGroup && count($components) > 1)
    {
        // Add a new component to the group.
        if ($component->isRepeatable())
        {
            echo Html::a('<span class="fa fa-plus icon-margin small"></span> ' . Yii::t('backend', 'Add new {group} to the group ', [
                    'group' => $component->title,
                ]), [ 'component-duplicate', 'id' => $component->id ], [
                'class' => 'btn btn-sm btn-primary component-duplicate',
                'style' => 'margin-left: 5px;',
            ]);
        }

        // Delete component
        echo Html::a('<span class="fa fa-trash icon-margin small"></span> ' . Yii::t('backend', 'Delete Group'),
            [ 'component-delete-group', 'id' => $component->id ], [
                'class' => 'btn  btn-sm btn-danger pull-right component-delete-group',
                'style' => 'margin-left: 5px;',
            ]);

        BoxPanel::end();
    }
}

$confirm                 = \Yii::t('backend', 'Are you sure?');
$delete_msg              = \Yii::t('backend', 'The component will be deleted');
$duplication_title       = \Yii::t('backend', 'Duplicate the component?');
$duplication_msg         = \Yii::t('backend', 'The component will be duplicated');
$duplication_field_title = \Yii::t('backend', 'Duplicate the Field?');
$duplication_field_msg   = \Yii::t('backend', 'The field will be duplicated');

$script = /** @lang JavaScript */
    <<< JS
// COMPONENT-GROUP DELETE THROUGH AJAX.
$('body').off('click', ".component-delete-group");
$('body').on('click', '.component-delete-group', function (event) {
    event.preventDefault();
    // Save variables.
    var element = $(this);
    var box = element.closest('.box');
    var url = element.attr('href');
    // Confirm that the user wants to continue.
    swal({
            title: "$confirm",
            text: "$delete_msg",
            type: "error",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
        },
        function () {
            showContentLoaderActivator(true);
            // Send the request to the server to remove this group.
            $.ajax({
                url: url,
                type: 'POST',
                success: function (data) {
                    window.refreshContentUi(data.id, data.msg, data.opened_boxes);
                    swal.close();
                }
            });
        });
});

// SINGLE COMPONENT DELETE THROUGH AJAX.
$('body').off('click', ".component-delete");
$('body').on('click', '.component-delete', function (event) {
    event.preventDefault();

    // Save variables.
    var element = $(this);
    var box = element.closest('.box');
    var url = element.attr('href');

    // Confirm that the user wants to continue.
    swal({
            title: "$confirm",
            text: "$delete_msg",
            type: "error",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
        },
        function () {
            showContentLoaderActivator(true);
            // Send the request to the server to remove this group.
            $.ajax({
                url: url,
                type: 'POST',
                success: function (data) {
                    window.refreshContentUi(data.id, data.msg, data.opened_boxes);
                    swal.close();
                }
            });
        });
});

// COMPONENT DUPLICATE
$('body').off('click', '.component-duplicate');
$('body').on('click', '.component-duplicate', function (event) {
    event.preventDefault();

    // Save variables.
    var element = $(this);
    var box = element.closest('.sortable');
    var group_id = box.attr('id');
    var url = element.attr('href');

    // Confirm the duplication
    swal({
            title: "$duplication_title",
            text: "$duplication_msg",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        },
        function () {
            showContentLoaderActivator(true);
            // Dissallow the Text areas.
            window.destroyCKE();
            // Send the request to the server to add a new component duplication.
            $.ajax({
                url: url,
                type: 'POST',
                success: function (data) {
                    window.refreshContentUi(data.id, data.msg, data.opened_boxes);
                    swal.close();
                }
            });
        });
});

JS;
$this->registerJs($script);