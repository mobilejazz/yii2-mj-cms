<?php

namespace mobilejazz\yii2\cms\common\components;

use yii;
use yii\base\Component;

class RefererBasedLocaleOverride extends Component
{
    /**
     * @var array
     */
    public $config = [];

    public function init()
    {
        $referer = Yii::$app->request->getHeaders()->get('referer');

        $localeOverride = null;

        foreach ($this->config as $pattern => $locale) {

            if (preg_match($pattern, $referer)) {
                $localeOverride = $locale;
                break;
            }

        }

        Yii::trace("RefererBasedLocaleOverride: referer = $referer, config = $localeOverride");

        if (isset($localeOverride)) {
            Yii::$app->language = $localeOverride;
            Yii::$app->params['country'] = explode('_', $localeOverride)[1];
        }

        parent::init();
    }


}