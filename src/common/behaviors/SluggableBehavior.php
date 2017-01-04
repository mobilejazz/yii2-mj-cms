<?php

namespace mobilejazz\yii2\cms\common\behaviors;

use yii\helpers\Inflector;

class SluggableBehavior extends \yii\behaviors\SluggableBehavior
{

    /**
     * This method is called by [[getValue]] to generate the slug.
     * You may override it to customize slug generation.
     * The default implementation calls [[\yii\helpers\Inflector::slug()]] on the input strings
     * concatenated by dashes (`-`).
     *
     * @param array $slugParts an array of strings that should be concatenated and converted to generate the slug value.
     *
     * @return string the conversion result.
     */
    protected function generateSlug($slugParts)
    {
        $replacement = '-';
        $lowercase   = true;
        $string      = implode('-', $slugParts);
        $string      = Inflector::transliterate($string);
        $string      = preg_replace('/[^a-zA-Z\/0-9=\s—–-]+/u', '', $string);
        $string      = preg_replace('/[=\s—–-]+/u', $replacement, $string);
        $string      = trim($string, $replacement);

        return $lowercase ? strtolower($string) : $string;
    }

}