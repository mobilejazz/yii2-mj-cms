<?php

use mobilejazz\yii2\cms\backend\modules\filemanager\widgets\FileInput;
use mobilejazz\yii2\cms\common\models\Fields;
use kartik\color\ColorInput;
use kartik\widgets\SwitchInput;
use trntv\aceeditor\AceEditor;
use yii\widgets\ActiveForm;

/**
 * @noinspection PhpUnusedParameterInspection
 *
 * This configuration file returns all the fields that
 * can be used inside components.
 * 1- You can define a NAME (please use the Yii::t function
 * so translations are correctly used.
 * 2- The WIDGET is the actual field that renders this particular
 * type of field.
 * 3- You can also use the RULES When defining rules for each field.
 * Remember that the 'text' is the field we will be validating ALWAYS.
 * If you need help setting up rules, you can take a look here:
 * http://www.bsourcecode.com/yiiframework2/validation-rules-for-model-attributes-in-yiiframework-2-0/
 */
return [

    Fields::FIELD_SUBTITLE => [
        'name'   => Yii::t('app', 'Subtitle'),
        'rules'  => [
            [ [ 'text' ], 'required' ],
            [ [ 'text' ], 'string', 'max' => 200 ],
        ],
        'widget' => function ($form = null, $field, $label, $placeholder, $hint, $options)
        {
            return Fields::field($form, $field, "[$field->id]text", $options)
                         ->textInput([
                             'placeholder' => !empty($placeholder) ? $placeholder : false,
                         ])
                         ->label($label)
                         ->hint(!empty($hint) ? $hint : false);
        },
    ],

    Fields::FIELD_EMAIL => [
        'name'   => \Yii::t('app', 'E-Mail'),
        'rules'  => [
            [ 'text', 'required' ],
            [ 'text', 'email' ],
        ],
        'widget' => function ($form = null, $field, $label, $placeholder, $hint, $options)
        {
            return Fields::field($form, $field, "[$field->id]text", $options)
                         ->textInput([
                             'placeholder' => !empty($placeholder) ? $placeholder : false,
                         ])
                         ->label($label)
                         ->hint(!empty($hint) ? $hint : false);
        },
    ],

    Fields::FIELD_TITLE => [
        'name'   => Yii::t('app', 'Title'),
        'rules'  => [
            [ [ 'text' ], 'required' ],
            [ [ 'text' ], 'string', 'max' => 200 ],
        ],
        'widget' => function ($form = null, $field, $label, $placeholder, $hint, $options)
        {
            return Fields::field($form, $field, "[$field->id]text", $options)
                         ->textInput([
                             'placeholder' => !empty($placeholder) ? $placeholder : false,
                         ])
                         ->label($label)
                         ->hint(!empty($hint) ? $hint : false);
        },
    ],

    Fields::FIELD_TITLE_BOLD => [
        'name'   => Yii::t('app', 'Bold Title'),
        'rules'  => [
            [ [ 'text' ], 'required' ],
            [ [ 'text' ], 'string', 'max' => 200 ],
        ],
        'widget' => function ($form = null, $field, $label, $placeholder, $hint, $options)
        {
            return Fields::field($form, $field, "[$field->id]text", $options)
                         ->textInput([
                             'placeholder' => !empty($placeholder) ? $placeholder : false,
                         ])
                         ->label($label)
                         ->hint(!empty($hint) ? $hint : false);
        },
    ],

    Fields::FIELD_TITLE_NON_BOLD => [
        'name'   => Yii::t('app', 'Non Bold Title'),
        'rules'  => [
            [ [ 'text' ], 'required' ],
            [ [ 'text' ], 'string', 'max' => 200 ],
        ],
        'widget' => function ($form = null, $field, $label, $placeholder, $hint, $options)
        {
            return Fields::field($form, $field, "[$field->id]text", $options)
                         ->textInput([
                             'placeholder' => !empty($placeholder) ? $placeholder : false,
                         ])
                         ->label($label)
                         ->hint(!empty($hint) ? $hint : false);
        },
    ],

    Fields::FIELD_TEXT_BOX                           => [
        'name'   => Yii::t('app', 'Text Box'),
        'rules'  => [
            [ [ 'text' ], 'required' ],
            [ [ 'text' ], 'string', 'max' => 200 ],
        ],
        'widget' => function ($form = null, $field, $label, $placeholder, $hint, $options)
        {
            return Fields::field($form, $field, "[$field->id]text", $options)
                         ->textInput([
                             'placeholder' => !empty($placeholder) ? $placeholder : false,
                         ])
                         ->label($label)
                         ->hint(!empty($hint) ? $hint : false);
        },
    ],
    Fields::FIELD_PASSWORD                           => [
        'name'   => Yii::t('app', 'Password'),
        'rules'  => [
            //  [ [ 'text' ], 'required' ],
            //  [ [ 'text' ], 'string', 'max' => 200 ],
        ],
        'widget' => function ($form = null, $field, $label, $placeholder, $hint, $options)
        {
            /** @var ActiveForm $form */
            return Fields::field($form, $field, "[$field->id]text", $options)
                         ->passwordInput([
                             'placeholder' => !empty($placeholder) ? $placeholder : false,
                         ])
                         ->label($label);
        },
    ],
    Fields::FIELD_TEXT_AREA                          => [
        'name'   => Yii::t('app', 'Text Area'),
        'rules'  => [
            //  [ [ 'text' ], 'required' ],
            //  [ [ 'text' ], 'string' ],
        ],
        'widget' => function ($form = null, $field, $label, $placeholder, $hint, $options)
        {
            /** @var ActiveForm $form */
            return Fields::field($form, $field, "[$field->id]text", $options)
                         ->textArea([
                             'rows' => '6',
                             'placeholder' => !empty($placeholder) ? $placeholder : false,
                         ])
                         ->label($label);
        },
    ],
    Fields::FIELD_CMS_COLOR_PALETTE_BACKGROUND => [
        'name'   => \Yii::t('app', 'CMS Color Palette'),
        'widget' => function ($form = null, $field, $label, $placeholder, $hint, $options)
        {
            return Fields::field($form, $field, "[$field->id]text", $options)
                         ->dropdownList([
                             'background-navy-blue'      => \Yii::t('app', 'Navy Blue'),
                             'background-blue'           => \Yii::t('app', 'Blue'),
                             'background-cobalt-blue'    => \Yii::t('app', 'Cobalt Blue'),
                             'background-medium-blue'    => \Yii::t('app', 'Medium Blue'),
                             'background-sky-blue'       => \Yii::t('app', 'Sky Blue'),
                             'background-light-blue'     => \Yii::t('app', 'Light Blue'),
                             'background-blue-gray'      => \Yii::t('app', 'Blue Gray'),
                             'background-dark-gray'      => \Yii::t('app', 'Dark Gray'),
                             'background-light-gray'     => \Yii::t('app', 'Light Gray'),
                             'background-white'          => \Yii::t('app', 'White'),
                             'background-yellow'         => \Yii::t('app', 'Yellow'),
                             'background-light-orange'   => \Yii::t('app', 'Light Orange'),
                             'background-orange'         => \Yii::t('app', 'Orange'),
                             'background-purple'         => \Yii::t('app', 'Purple'),
                             'background-green'          => \Yii::t('app', 'Green'),
                             'background-turquoise'      => \Yii::t('app', 'Turquoise'),
                         ])
                         ->label($label);
        }
    ],

    Fields::FIELD_CMS_COLOR_PALETTE_TEXT => [
        'name'   => \Yii::t('app', 'CMS Color Palette'),
        'widget' => function ($form = null, $field, $label, $placeholder, $hint, $options)
        {
            return Fields::field($form, $field, "[$field->id]text", $options)
                         ->dropdownList([
                             'text-navy-blue'      => \Yii::t('app', 'Navy Blue'),
                             'text-blue'           => \Yii::t('app', 'Blue'),
                             'text-cobalt-blue'    => \Yii::t('app', 'Cobalt Blue'),
                             'text-medium-blue'    => \Yii::t('app', 'Medium Blue'),
                             'text-sky-blue'       => \Yii::t('app', 'Sky Blue'),
                             'text-light-blue'     => \Yii::t('app', 'Light Blue'),
                             'text-blue-gray'      => \Yii::t('app', 'Blue Gray'),
                             'text-dark-gray'      => \Yii::t('app', 'Dark Gray'),
                             'text-light-gray'     => \Yii::t('app', 'Light Gray'),
                             'text-white'          => \Yii::t('app', 'White'),
                             'text-yellow'         => \Yii::t('app', 'Yellow'),
                             'text-light-orange'   => \Yii::t('app', 'Light Orange'),
                             'text-orange'         => \Yii::t('app', 'Orange'),
                             'text-purple'         => \Yii::t('app', 'Purple'),
                             'text-green'          => \Yii::t('app', 'Green'),
                             'text-turquoise'      => \Yii::t('app', 'Turquoise'),
                         ])
                         ->label($label);
        }
    ],

    Fields::FIELD_IMAGE => [
        'name'   => Yii::t('app', 'Image'),
        'widget' => function ($form = null, $field, $label, $placeholder, $hint, $options)
        {
            /** @var ActiveForm $form */
            return Fields::field($form, $field, "[$field->id]text", $options)
                         ->widget(FileInput::className(), [
                             'buttonTag'            => 'button',
                             'buttonName'           => 'Browse',
                             'buttonOptions'        => [ 'class' => 'btn btn-default' ],
                             'options'              => [ 'class' => 'form-control' ],
                             // Widget template
                             'template'             => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
                             'thumb'                => 'original',
                             'pasteData'            => FileInput::DATA_URL,
                             'callbackBeforeInsert' => 'function(e, data) {
                                    console.log(data);
                                    console.log(e);
                              }',
                         ])
                         ->label($label);
        },
    ],

    Fields::FIELD_HTML_CODE => [
        'name'   => \Yii::t('app', 'HTML Code'),
        'widget' => function ($form = null, $field, $label, $placeholder, $hint, $options)
        {
            return Fields::field($form, $field, "[$field->id]text", $options)
                         ->widget(AceEditor::className(), [
                             'mode'    => 'html',
                             'theme'   => 'github',
                             'options' => [
                                 'class' => 'ace-editor',
                             ],
                         ])
                         ->label($label);
        },
    ],

    Fields::FIELD_CHECKBOX => [
        'name'   => Yii::t('app', 'Checkbox'),
        'rules'  => [
        ],
        'widget' => function ($form = null, $field, $label, $placeholder, $hint, $options)
        {

            /** @var ActiveForm $form */
            return Fields::field($form, $field, "[$field->id]text", $options)
                ->checkBox([
                    'label' => $label,
                    'labelOptions' => [
                        'class' => 'checkbox'
                    ]
                ], true)
                ->hint($hint);
        },
    ],

    Fields::FIELD_LINK_NAME        => [
        'name'   => Yii::t('app', 'Link Name'),
        /** @var ActiveForm $form */
        'widget' => function ($form = null, $field, $label, $placeholder, $hint, $options)
        {
            /** @var ActiveForm $form */
            return Fields::field($form, $field, "[$field->id]text", $options)
                         ->textInput([
                             'placeholder' => !empty($placeholder) ? $placeholder : false,
                         ])
                         ->label($label)
                         ->hint(!empty($hint) ? $hint : false);
        },
    ],
    Fields::FIELD_LINK_URL         => [
        'name'   => Yii::t('app', 'Link URL'),
        /** @var ActiveForm $form */
        'widget' => function ($form = null, $field, $label, $placeholder, $hint, $options)
        {
            /** @var ActiveForm $form */
            return Fields::field($form, $field, "[$field->id]text", $options)
                         ->textInput([
                             'placeholder' => !empty($placeholder) ? $placeholder : false,
                         ])
                         ->label($label)
                         ->hint(!empty($hint) ? $hint : false);
        },
    ],
    Fields::FIELD_LINK_COLOR       => [
        'name'   => \Yii::t('app', 'Link Color'),
        'widget' => function ($form = null, $field, $label, $placeholder, $hint, $options)
        {
            return Fields::field($form, $field, "[$field->id]text", $options)
                         ->dropdownList([
                             'button-navy-blue'      => \Yii::t('app', 'Navy Blue'),
                             'button-blue'           => \Yii::t('app', 'Blue'),
                             'button-cobalt-blue'    => \Yii::t('app', 'Cobalt Blue'),
                             'button-medium-blue'    => \Yii::t('app', 'Medium Blue'),
                             'button-sky-blue'       => \Yii::t('app', 'Sky Blue'),
                             'button-light-blue'     => \Yii::t('app', 'Light Blue'),
                             'button-blue-gray'      => \Yii::t('app', 'Blue Gray'),
                             'button-dark-gray'      => \Yii::t('app', 'Dark Gray'),
                             'button-light-gray'     => \Yii::t('app', 'Light Gray'),
                             'button-white'          => \Yii::t('app', 'White'),
                             'button-yellow'         => \Yii::t('app', 'Yellow'),
                             'button-light-orange'   => \Yii::t('app', 'Light Orange'),
                             'button-orange'         => \Yii::t('app', 'Orange'),
                             'button-purple'         => \Yii::t('app', 'Purple'),
                             'button-green'          => \Yii::t('app', 'Green'),
                             'button-turquoise'      => \Yii::t('app', 'Turquoise'),
                         ])
                         ->label($label);
        }
    ],
    Fields::FIELD_LINK_TARGET      => [
        'name'   => Yii::t('app', 'Link Target'),
        'widget' => function ($form = null, $field, $label, $placeholder, $hint, $options)
        {
            /** @var ActiveForm $form */
            return Fields::field($form, $field, "[$field->id]text", $options)
                         ->widget(SwitchInput::className(), [
                             'pluginOptions' => [
                                 'handleWidth' => 100,
                                 'onText'      => Yii::t('app', 'Same Window'),
                                 'offText'     => Yii::t('app', 'New Window'),
                             ],
                         ])
                         ->label($label);
        },
    ],
    Fields::FIELD_HEX_COLOR        => [
        'name'   => Yii::t('app', 'HEX Color'),
        'widget' => function ($form = null, $field, $label, $placeholder, $hint, $options)
        {
            /** @var ActiveForm $form */
            return Fields::field($form, $field, "[$field->id]text", $options)
                         ->widget(ColorInput::className(), [
                             'options' => [
                                 'placeholder' => !empty($placeholder) ? $placeholder : Yii::t('app', 'Select color...'),
                             ],
                         ])
                         ->label($label);
        },
    ],
    Fields::FIELD_BG_COLOR         => [
        'name'   => Yii::t('app', 'BG Color'),
        'widget' => function ($form = null, $field, $label, $placeholder, $hint, $options)
        {
            /** @var ActiveForm $form */
            return Fields::field($form, $field, "[$field->id]text", $options)
                         ->widget(ColorInput::className(), [
                             'options' => [
                                 'placeholder' => !empty($placeholder) ? $placeholder : Yii::t('app', 'Select color...'),
                             ],
                         ])
                         ->label($label);
        },
    ],
    Fields::FIELD_FRAME_COLOR      => [
        'name'   => \Yii::t('app', 'Frame Color'),
        'widget' => function ($form = null, $field, $label, $placeholder, $hint, $options)
        {
            return Fields::field($form, $field, "[$field->id]text", $options)
                         ->dropdownList([
                             'text-navy-blue'      => \Yii::t('app', 'Navy Blue'),
                             'text-blue'           => \Yii::t('app', 'Blue'),
                             'text-cobalt-blue'    => \Yii::t('app', 'Cobalt Blue'),
                             'text-medium-blue'    => \Yii::t('app', 'Medium Blue'),
                             'text-sky-blue'       => \Yii::t('app', 'Sky Blue'),
                             'text-light-blue'     => \Yii::t('app', 'Light Blue'),
                             'text-blue-gray'      => \Yii::t('app', 'Blue Gray'),
                             'text-dark-gray'      => \Yii::t('app', 'Dark Gray'),
                             'text-light-gray'     => \Yii::t('app', 'Light Gray'),
                             'text-white'          => \Yii::t('app', 'White'),
                             'text-yellow'         => \Yii::t('app', 'Yellow'),
                             'text-light-orange'   => \Yii::t('app', 'Light Orange'),
                             'text-orange'         => \Yii::t('app', 'Orange'),
                             'text-purple'         => \Yii::t('app', 'Purple'),
                             'text-green'          => \Yii::t('app', 'Green'),
                             'text-turquoise'      => \Yii::t('app', 'Turquoise'),
                         ])
                         ->label($label);
        }
    ],
    Fields::FIELD_CSS_CLASS        => [
        'name'   => Yii::t('app', 'CSS Class'),
        /** @var ActiveForm $form */
        'widget' => function ($form = null, $field, $label, $placeholder, $hint, $options)
        {
            /** @var ActiveForm $form */
            return Fields::field($form, $field, "[$field->id]text", $options)
                         ->textInput([
                             'placeholder' => !empty($placeholder) ? $placeholder : false,
                         ])
                         ->label($label)
                         ->hint(!empty($hint) ? $hint : false);
        },
    ],
    Fields::FIELD_LTR_SWITCH_INPUT => [
        'name'   => Yii::t('app', 'Alignment (L/R)'),
        'widget' => function ($form = null, $field, $label, $placeholder, $hint, $options)
        {
            /** @var ActiveForm $form */
            return Fields::field($form, $field, "[$field->id]text", $options)
                         ->widget(SwitchInput::className(), [
                             'pluginOptions' => [
                                 'handleWidth' => 60,
                                 'onText'      => 'LTR',
                                 'offText'     => 'RTL',
                             ],
                         ])
                         ->label($label);
        },
    ],
    Fields::FIELD_BOLD_SELECTOR    => [
        'name'   => Yii::t('app', 'Bold text'),
        'widget' => function ($form = null, $field, $label, $placeholder, $hint, $options)
        {
            /** @var ActiveForm $form */
            return Fields::field($form, $field, "[$field->id]text", $options)
                         ->widget(SwitchInput::className(), [
                             'pluginOptions' => [
                                 'handleWidth' => 60,
                                 'onText'      => 'Yes',
                                 'offText'     => 'No',
                             ],
                         ])
                         ->label($label);
        },
    ],

    Fields::FIELD_IS_OPEN => [
        'name'   => Yii::t('app', 'Is Open'),
        'widget' => function ($form = null, $field, $label, $placeholder, $hint, $options)
        {
            /** @var ActiveForm $form */
            return Fields::field($form, $field, "[$field->id]text", $options)
                         ->widget(SwitchInput::className(), [
                             'pluginOptions' => [
                                 'handleWidth' => 60,
                                 'open'        => 'Yes',
                                 ''            => 'No',
                             ],
                         ])
                         ->label($label);
        },
    ],

    Fields::FIELD_BUTTON_TEXT => [
        'name'   => Yii::t('app', 'Button Text'),
        /** @var ActiveForm $form */
        'widget' => function ($form = null, $field, $label, $placeholder, $hint, $options)
        {
            /** @var ActiveForm $form */
            return Fields::field($form, $field, "[$field->id]text", $options)
                         ->textInput([
                             'placeholder' => !empty($placeholder) ? $placeholder : false,
                         ])
                         ->label($label)
                         ->hint(!empty($hint) ? $hint : false);
        },
    ],
    Fields::FIELD_ICON        => [
        'name'   => Yii::t('app', 'Icon class'),
        /** @var ActiveForm $form */
        'widget' => function ($form = null, $field, $label, $placeholder, $hint, $options)
        {
            /** @var ActiveForm $form */
            return Fields::field($form, $field, "[$field->id]text", $options)
                         ->textInput([
                             'placeholder' => !empty($placeholder) ? $placeholder : false,
                         ])
                         ->label($label)
                         ->hint(!empty($hint) ? $hint : false);
        },
    ],
    Fields::FIELD_FILE        => [
        'name'   => Yii::t('app', 'File'),
        'widget' => function ($form = null, $field, $label, $placeholder, $hint, $options)
        {
            /** @var ActiveForm $form */
            return Fields::field($form, $field, "[$field->id]text", $options)
                         ->widget(FileInput::className(), [
                             'buttonTag'            => 'button',
                             'buttonName'           => 'Browse',
                             'buttonOptions'        => [ 'class' => 'btn btn-default' ],
                             'options'              => [ 'class' => 'form-control' ],
                             // Widget template
                             'template'             => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
                             'thumb'                => 'original',
                             'pasteData'            => FileInput::DATA_URL,
                             'callbackBeforeInsert' => 'function(e, data) {
                               console.log(data);
                               console.log(e);
                    }',
                         ])
                         ->label($label);
        },
    ],

    Fields::FIELD_BUTTON_ALIGNMENT => [
        'name'   => \Yii::t('backend', 'Button Alignment'),
        'widget' => function ($form = null, $field, $label, $placeholder, $hint, $options)
        {
            return Fields::field($form, $field, "[$field->id]text", $options)
                         ->dropdownList([
                             'text-left'   => \Yii::t('backend', 'Left'),
                             'text-center' => \Yii::t('backend', 'Center'),
                             'text-right'  => \Yii::t('backend', 'Right'),
                         ])
                         ->label($label);
        }
    ],
];