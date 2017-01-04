<?php

namespace mobilejazz\yii2\cms\common\widgets;

use yii;
use yii\bootstrap\ActiveField as BaseActiveField;
use yii\helpers\Html;
use yii\web\JsExpression;

/**
 * Override the Base ActiveField class to avoid problems with our
 * own component-field-based content system.
 * Class ActiveField
 * @package common\widgets
 */
class ActiveField extends BaseActiveField
{

    /**
     * Renders a widget as the input of the field.
     *
     * Note that the widget must have both `model` and `attribute` properties. They will
     * be initialized with [[model]] and [[attribute]] of this field, respectively.
     *
     * If you want to use a widget that does not have `model` and `attribute` properties,
     * please use [[render()]] instead.
     *
     * For example to use the [[MaskedInput]] widget to get some date input, you can use
     * the following code, assuming that `$form` is your [[ActiveForm]] instance:
     *
     * ```php
     * $form->field($model, 'date')->widget(\yii\widgets\MaskedInput::className(), [
     *     'mask' => '99/99/9999',
     * ]);
     * ```
     *
     * If you set a custom `id` for the input element, you may need to adjust the [[$selectors]] accordingly.
     *
     * @param string $class  the widget class name
     * @param array  $config name-value pairs that will be used to initialize the widget
     *
     * @return $this the field object itself
     */
    public function widget($class, $config = [ ])
    {
        /* @var $class \yii\base\Widget */
        $config[ 'model' ]        = $this->model;
        $config[ 'attribute' ]    = $this->attribute;
        $config[ 'view' ]         = \Yii::$app->getView();
        $this->parts[ '{input}' ] = $class::widget($config);

        return $this;
    }


    /**
     * Returns the JS options for the field.
     * @return array the JS options
     */
    protected function getClientOptions()
    {
        $attribute = Html::getAttributeName($this->attribute);
        if (!in_array($attribute, $this->model->activeAttributes(), true))
        {
            return [ ];
        }

        $enableClientValidation = $this->enableClientValidation || $this->enableClientValidation === null && $this->form->enableClientValidation;
        $enableAjaxValidation   = $this->enableAjaxValidation || $this->enableAjaxValidation === null && $this->form->enableAjaxValidation;

        if ($enableClientValidation)
        {
            $validators = [ ];
            foreach ($this->model->getActiveValidators($attribute) as $validator)
            {
                /* @var $validator \yii\validators\Validator */
                $js = $validator->clientValidateAttribute($this->model, $attribute, \Yii::$app->getView());
                if ($validator->enableClientValidation && $js != '')
                {
                    if ($validator->whenClient !== null)
                    {
                        $js = "if (({$validator->whenClient})(attribute, value)) { $js }";
                    }
                    $validators[] = $js;
                }
            }
        }

        if (!$enableAjaxValidation && (!$enableClientValidation || empty($validators)))
        {
            return [ ];
        }

        $options = [ ];

        $inputID           = Html::getInputId($this->model, $this->attribute);
        $options[ 'id' ]   = $inputID;
        $options[ 'name' ] = $this->attribute;

        $options[ 'container' ] = isset($this->selectors[ 'container' ]) ? $this->selectors[ 'container' ] : ".field-$inputID";
        $options[ 'input' ]     = isset($this->selectors[ 'input' ]) ? $this->selectors[ 'input' ] : "#$inputID";
        if (isset($this->selectors[ 'error' ]))
        {
            $options[ 'error' ] = $this->selectors[ 'error' ];
        }
        elseif (isset($this->errorOptions[ 'class' ]))
        {
            $options[ 'error' ] = '.' . implode('.', preg_split('/\s+/', $this->errorOptions[ 'class' ], -1, PREG_SPLIT_NO_EMPTY));
        }
        else
        {
            $options[ 'error' ] = isset($this->errorOptions[ 'tag' ]) ? $this->errorOptions[ 'tag' ] : 'span';
        }

        $options[ 'encodeError' ] = !isset($this->errorOptions[ 'encode' ]) || $this->errorOptions[ 'encode' ];
        if ($enableAjaxValidation)
        {
            $options[ 'enableAjaxValidation' ] = true;
        }
        foreach ([ 'validateOnChange', 'validateOnBlur', 'validateOnType', 'validationDelay' ] as $name)
        {
            $options[ $name ] = $this->$name === null ? $this->form->$name : $this->$name;
        }

        if (!empty($validators))
        {
            $options[ 'validate' ] = new JsExpression("function (attribute, value, messages, deferred, \$form) {" . implode('', $validators) . '}');
        }

        // only get the options that are different from the default ones (set in yii.activeForm.js)
        return array_diff_assoc($options, [
            'validateOnChange' => true,
            'validateOnBlur'   => true,
            'validateOnType'   => false,
            'validationDelay'  => 500,
            'encodeError'      => true,
            'error'            => '.help-block',
        ]);
    }
}