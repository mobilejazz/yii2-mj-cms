<?php

namespace mobilejazz\yii2\cms\frontend\widgets;

use yii\base\InvalidConfigException;
use yii\helpers\Html;

class ActiveForm extends \yii\widgets\ActiveForm
{

    public $fieldClass = 'mobilejazz\yii2\cms\frontend\widgets\ActiveField';

    public $layout = 'default';


    /**
     * @inheritdoc
     */
    public function init()
    {
        if (!in_array($this->layout, [ 'default', 'inline' ]))
        {
            throw new InvalidConfigException('Invalid layout type: ' . $this->layout);
        }
        if ($this->layout !== 'default')
        {
            Html::addCssClass($this->options, 'form-' . $this->layout);
        }

        // ADD class for Floating Labels.
        Html::addCssClass($this->options, 'floating-label-form');
        parent::init();
    }

}