<?php

namespace mobilejazz\yii2\cms\backend\modules\i18n\components;

use yii\i18n\DbMessageSource;

/**
 * TODO refine
 * Class CategoryOverrideMessageSource
 */
class CategoryOverrideDbMessageSource extends DbMessageSource {

    /**
     * @var string Used to override the category during translation
     */
    public $categoryOverride;

    protected function translateMessage($category, $message, $language)
    {
        return parent::translateMessage($this->categoryOverride, $message, $language);
    }


}