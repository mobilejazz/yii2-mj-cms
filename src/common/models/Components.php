<?php

namespace mobilejazz\yii2\cms\common\models;

use yii;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;

class Components
{

    // THE FOLLOWING IS THE COMPLETE LIST OF COMPONENTS WE HAVE DEFINED IN THIS PROJECT.
    const COMP_BUTTON = 'button';
    const COMP_EXPANDABLE_CONTENT = 'expandable-content';
    const COMP_EXPANDABLE_CONTENT_INNER = 'expandable-content-inner';
    const COMP_HEADING = 'heading';
    const COMP_HIGHLIGHTED_IMAGE = 'highlighted-image';
    const COMP_HTML_CODE = 'html-code';
    const COMP_IMAGE = 'image';
    const COMP_IMAGE_WITH_CAPTION_AND_BACKGROUND = 'image-with-caption-and-background';
    const COMP_INTRODUCTION_TEXT = 'introduction-text';
    const COMP_INTRODUCTION_TEXT_NO_TITLE = 'introduction-text-no-title';
    const COMP_INTRODUCTION_TEXT_TITLE_ONLY = 'introduction-text-title-only';
    const COMP_INTRODUCTION_TEXT_TITLE_ONLY_SINGLE_LINE = 'introduction-text-title-only-single-line';
    const COMP_INTRODUCTION_TEXT_TWO_LINES = 'introduction-text-two-lines';
    const COMP_LINK = 'link';
    const COMP_REFERENCES = 'references';
    const COMP_TEXT_RESOURCE = 'text-resource';
    const COMP_TEXT_RESOURCE_GROUP = 'text-resource-group';
    const COMP_TEXT_WITHOUT_HEADING = 'text-without-heading';
    const COMP_TEXT_WITH_HEADING = 'text-with-heading';
    const COMP_TEXT_WITH_HEADING_2_COLS = 'text-with-heading-2-col-layout';
    const COMP_TEXT_WITH_HEADING_NO_LINE_SELECTABLE_TITLE_COLOR = 'text-with-heading-no-line-selectable-title-color';
    const COMP_TEXT_WITH_MEDIA = 'text-with-media';
    const COMP_TEXT_WITH_MEDIA_WITH_BUTTON = 'text-with-media-with-button';
    const COMP_GRID = 'grid';
    const COMP_GRID_ELEMENT = 'grid-element';


    /**
     * @return array[] of views
     */
    public static function asMap()
    {
        $array_to_return = [];

        $components = self::getComponents();
        foreach ($components as $key => $component)
        {
            $array_to_return[ $key ] = $component[ 'name' ];
        }

        return $array_to_return;
    }


    /**
     * Load the components.
     * @return mixed|null
     */
    public static function getComponents()
    {
        static $config = null;

        if ($config == null)
        {
            /** @noinspection PhpIncludeInspection */

            $params = \Yii::$app->params[ 'cms' ];

            Yii::info("Components params " . json_encode(Yii::$app->params));

            if (isset($params))
            {
                $params = $params[ 'components' ];
            }

            $config = require(\Yii::getAlias('@mobilejazz/yii2/cms/common/config/content/components.php'));

            foreach ($params as $file)
            {
                Yii::info('Loading components config from ' . $file);
                $contents = require(\Yii::getAlias($file));
                $config   = ArrayHelper::merge($config, $contents);
            }

            $json = json_encode($config);
            Yii::info("Components config", $json);

            $config = array_merge([
                'form' => [
                    'name'   => Yii::t('backend', 'Form'),
                    'fields' => [
                        'form-dropdown' => [],
                    ],
                ],
            ], $config);
        }

        return $config;
    }


    public static function getFields($id)
    {
        return self::getComponent($id)[ 'fields' ];
    }


    public static function getComponent($id)
    {
        foreach (self::getComponents() as $key => $component)
        {
            if ($key == $id)
            {
                return $component;
            }
        }
        throw new BadRequestHttpException(Yii::t('backend', "The component $id does not exist."));
    }


    public static function getInnerComponents($id)
    {
        return self::getComponent($id)[ 'inner-components' ];
    }


    /**
     * @param $view_id
     * @param $type
     *
     * @return boolean true if is Groupable, false otherwise?
     */
    public static function isGroupable($view_id, $type)
    {
        if (isset(Views::getStructure($view_id)[ $type ][ 'groupable' ]))
        {
            return Views::getStructure($view_id)[ $type ][ 'groupable' ];
        }
        if (isset(self::getComponent($type)[ 'groupable' ]))
        {
            return self::getComponent($type)[ 'groupable' ];
        }

        return false;
    }


    /**
     * @param $view_id
     * @param $type
     *
     * @return boolean true if is Groupable, false otherwise?
     */
    public static function isRepeatable($view_id, $type)
    {
        if (isset(Views::getStructure($view_id)[ $type ][ 'repeatable' ]))
        {
            return Views::getStructure($view_id)[ $type ][ 'repeatable' ];
        }
        if (isset(self::getComponent($type)[ 'repeatable' ]))
        {
            return self::getComponent($type)[ 'repeatable' ];
        }

        return false;
    }


    public static function getFrontendSingleView($id)
    {
        return self::getComponent($id)[ 'frontend' ];
    }


    public static function getName($view = null, $component)
    {
        if (isset(Views::getStructure($view)[ $component ][ 'name' ]))
        {
            return Views::getStructure($view)[ $component ][ 'name' ];
        }

        return self::getComponent($component)[ 'name' ];
    }


    /**
     * @param $view_id
     * @param $type
     *
     * @return boolean true if is Groupable, false otherwise?
     */
    public static function displayTitle($view_id, $type)
    {
        if (isset(Views::getStructure($view_id)[ $type ][ 'title' ]))
        {
            return Views::getStructure($view_id)[ $type ][ 'title' ];
        }
        if (isset(self::getComponent($type)[ 'title' ]))
        {
            return self::getComponent($type)[ 'title' ];
        }

        return false;
    }


    /**
     * @param ContentComponent $component
     *
     * @return array
     */
    public static function getFieldsFromComponentAsArray($component)
    {
        return ArrayHelper::map($component->componentFields, 'type', 'text');
    }


    /**
     * Calculates the column size for the given number of components.
     *
     * @param $components
     *
     * @return array
     */
    public static function calculateInnerComponentColumnSize($components)
    {

        $tr = [];

        if ($components > 0)
        {
            $minSpan   = floor(12 / $components);
            $remainder = (12 % $components);

            for ($i = 0; $i < $components; $i++)
            {
                $width = $minSpan;
                if ($remainder > 0)
                {
                    $width += 1;
                    $remainder--;
                }
                $tr[ $i ] = $width;
            }
        }
        else
        {
            $tr[] = 12;
        }

        return $tr;
    }
}