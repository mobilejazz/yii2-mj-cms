<?php

namespace mobilejazz\yii2\cms\common\models;

use mobilejazz\yii2\cms\common\components\TimeStampActiveRecord;

/**
 * This is the base-model class for table "locale".
 *
 * @property integer $id
 * @property string  $lang
 * @property string  $label
 * @property string  $country_code
 * @property integer $default
 * @property integer $used
 * @property integer $rtl
 * @property integer $created_at
 * @property integer $updated_at
 */
class Locale extends TimeStampActiveRecord
{

    public $base_lang;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'locale';
    }


    public static function defaultToEnglish()
    {
        $english          = self::findOne(1);
        $english->default = 1;
        $english->used    = 1;
        $english->save();
    }


    public static function getAllKeys($only_used = false, $currently_used = true)
    {
        return array_keys(self::getAllLocalesAsMap($only_used, $currently_used));
    }


    public static function getAllLocalesAsMap($only_used = false, $currently_used = true)
    {
        $tr = [];
        /** @var Locale[] $all */
        $all = Locale::find()
                     ->all();
        foreach ($all as $t)
        {
            if (!$currently_used && self::getIdentifier($t) === \Yii::$app->language)
            {
                continue;
            }
            if ($only_used && !$t->isUsed())
            {
                continue;
            }
            $tr[ self::getIdentifier($t) ] = $t->label;
        }

        return $tr;
    }


    /**
     * @param $locale Locale
     *
     * @return string The identifier for this locale.
     */
    public static function getIdentifier($locale)
    {
        return strtolower($locale->lang . '_' . $locale->country_code);
    }


    /**
     * @return bool true if the language should be used, false otherwise.
     */
    public function isUsed()
    {
        return $this->used;
    }


    public static function getAllLabels()
    {
        $tr = [];
        /** @var Locale[] $all */
        $all = Locale::find()
                     ->all();
        foreach ($all as $t)
        {
            $tr[ $t->label ] = $t->label;
        }

        return $tr;
    }


    public static function getAllLocales()
    {
        $tr = [];
        /** @var Locale[] $all */
        $all = Locale::find()
                     ->all();
        foreach ($all as $t)
        {
            $tr[ $t->lang ] = $t->lang;
        }

        return $tr;
    }


    public static function getCurrentLanguageAsMap()
    {

        return [ \Yii::$app->language => self::getCurrent() ];
    }


    public static function getCurrent()
    {
        return self::getAllLocalesAsMap()[ \Yii::$app->language ];
    }


    public static function isCurrentRTL()
    {
        $parts = explode('_', \Yii::$app->language);

        $locale = self::findOne([ 'lang' => $parts[ 0 ], "country_code" => $parts[ 1 ] ]);

        $tr = boolval($locale->rtl);

        return $tr;
    }


    public static function getCurrentCountryCode()
    {
        return self::getAllCountryCodesAsMap()[ \Yii::$app->language ];
    }


    public static function getAllCountryCodesAsMap($only_used = false, $currently_used = true)
    {
        $tr = [];
        /** @var Locale[] $all */
        $all = Locale::find()
                     ->all();
        foreach ($all as $t)
        {
            if (!$currently_used && $t->lang === \Yii::$app->language)
            {
                continue;
            }
            if ($only_used && !$t->isUsed())
            {
                continue;
            }
            $tr[ self::getIdentifier($t) ] = $t->country_code;
        }

        return $tr;
    }


    public static function getCurrentLangCode()
    {
        return self::getAllLanguagesAsMap()[ \Yii::$app->language ];
    }


    public static function getAllLanguagesAsMap($only_used = false, $currently_used = true)
    {
        $tr = [];
        /** @var Locale[] $all */
        $all = Locale::find()
                     ->all();
        foreach ($all as $t)
        {
            if (!$currently_used && $t->lang === \Yii::$app->language)
            {
                continue;
            }
            if ($only_used && !$t->isUsed())
            {
                continue;
            }
            $tr[ self::getIdentifier($t) ] = $t->lang;
        }

        return $tr;
    }


    public static function getDefault()
    {
        return self::findOne([ 'default' => 1 ]);
    }


    public static function isLocaleUsed($lang_identifier)
    {
        $locale = self::findByIdentifier($lang_identifier);
        if (isset($locale))
        {
            return $locale->isUsed();
        }

        return false;
    }


    /**
     * @param $lang
     *
     * @return null|Locale
     */
    public static function findByIdentifier($lang)
    {
        $parts = explode('_', $lang);

        return self::findOne([ 'lang' => $parts[ 0 ], "country_code" => $parts[ 1 ] ]);
    }


    public static function isMultiLanguageSite()
    {
        return count(self::getUsedLocales()) > 1;
    }


    public static function getUsedLocales()
    {
        $tr = [];
        /** @var Locale[] $all */
        $all = Locale::find()
                     ->all();
        foreach ($all as $t)
        {
            if ($t->isUsed())
            {
                $tr[ self::getIdentifier($t) ] = $t->lang;
            }
        }

        return $tr;
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => \Yii::t('backend', 'ID'),
            'lang'         => \Yii::t('backend', 'Lang'),
            'label'        => \Yii::t('backend', 'Label'),
            'country_code' => \Yii::t('backend', 'Country Code'),
            'default'      => \Yii::t('backend', 'Default'),
            'used'         => \Yii::t('backend', 'Used'),
            'rtl'          => \Yii::t('backend', 'Right to Left'),
            'created_at'   => \Yii::t('backend', 'Created At'),
            'updated_at'   => \Yii::t('backend', 'Updated At'),
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'lang', 'label', 'country_code' ], 'required' ],
            [ [ 'default', 'used', 'created_at', 'updated_at' ], 'integer' ],
            [ [ 'used', 'rtl' ], 'default', 'value' => 0 ],
            [ [ 'lang', 'country_code' ], 'string', 'max' => 2 ],
            [ [ 'label' ], 'string', 'max' => 255 ],
        ];
    }


    public function isDefault()
    {
        return $this->default;
    }

}
