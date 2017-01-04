<?php

namespace mobilejazz\yii2\cms\common\validators;

use yii;
use yii\validators\Validator;

/**
 * Class JsonValidator
 * @package common\validators
 */
abstract class JsonValidator extends Validator
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->message === null)
        {
            $this->message = Yii::t('app', '"{attribute}" must be a valid JSON');
        }
    }


    /**
     * @inheritdoc
     */
    public function validateValue($value)
    {
        if (!@json_decode($value))
        {
            return [ $this->message, [] ];
        }

        return null;
    }


    /**
     * @inheritdoc
     */
    public function clientValidateAttribute($model, $attribute, $view)
    {
        $message = Yii::$app->getI18n()
                            ->format($this->message, [
                                'attribute' => $model->getAttributeLabel($attribute)
                            ], Yii::$app->language);

        $script = <<< JS
try {
    JSON.parse(value);
} catch (e) {
    messages.push('{$message}')
}
JS;

        return $script;
    }
}
