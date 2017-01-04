<?php

namespace mobilejazz\yii2\cms\common\validators;

use mobilejazz\yii2\cms\common\models\MenuItem;
use yii;
use yii\validators\Validator;

/**
 * Class ParentMenuValidator
 * @package common\validators
 */
class ParentMenuValidator extends Validator
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->message === null)
        {
            $this->message = Yii::t('app',
                '"{attribute}" seems to be invalid. Make sure that it the parent/child structure you have chosen makes sense.');
        }
    }


    /**
     * Validate that the new value (if new) can be
     * parsed as the new parent for this Menu Item.
     * @var $value int the new parent id.
     * @inheritdoc
     */
    public function validateValue($value, &$error = null)
    {
        if ($value == 0)
        {
            return null;
        }

        // Value is the new parent ID
        if (!isset($value))
        {
            return [ $this->message, [ ] ];
        }

        /** @var MenuItem $model This is the Menu we are updating. */
        $model = MenuItem::findOne(Yii::$app->request->get()[ 'id' ]);

        if (!isset($model))
        {
            return [ "It looks like the menu you are trying to edit no longer exists.", [ ] ];
        }
    }
}
