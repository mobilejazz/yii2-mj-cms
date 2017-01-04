<?php

namespace mobilejazz\yii2\cms\common\models;

use yii\helpers\ArrayHelper;

class Views
{

    /**
     * Return the key and the name of all the views as a map.
     * @return array[] of views
     */
    public static function asMap()
    {
        $array_to_return = [];

        $views = self::getViews();
        foreach ($views as $key => $view)
        {
            $array_to_return[ $key ] = $view[ 'name' ];
        }

        return $array_to_return;
    }


    /**
     * Return all the views array.
     * @return mixed|null
     */
    public static function getViews()
    {
        static $config = null;

        if ($config == null)
        {
            /** @noinspection PhpIncludeInspection */

            $params = \Yii::$app->params[ 'cms' ];
            if (isset($params))
            {
                $params = $params[ 'views' ];
            }

            $config = require(\Yii::getAlias('@mobilejazz/yii2/cms/common/config/content/views.php'));

            foreach ($params as $file)
            {
                $contents = require(\Yii::getAlias($file));
                $config   = ArrayHelper::merge($config, $contents);
            }

        }

        return $config;
    }


    /**
     * Gets the structure of Components a given View.
     *
     * @param $id
     *
     * @return null
     */
    public static function getStructure($id)
    {
        if (isset(Views::getView($id)[ 'components' ]))
        {
            return Views::getView($id)[ 'components' ];
        }

        return null;
    }


    /**
     * Gets a View given an ID (Key).
     *
     * @param $id
     *
     * @return null
     */
    public static function getView($id)
    {
        foreach (self::getViews() as $key => $view)
        {
            if ($key == $id)
            {
                return $view;
            }
        }

        return null;
    }


    /**
     * Returns the icon of a given view.
     *
     * @param $id
     *
     * @return null
     */
    public static function getViewIcon($id)
    {
        if (isset(Views::getView($id)[ 'icon' ]))
        {
            return Views::getView($id)[ 'icon' ];
        }

        return null;
    }


    /**
     * Returns the name of a given view.
     *
     * @param $id
     *
     * @return null
     */
    public static function getViewName($id)
    {
        if (isset(Views::getView($id)[ 'name' ]))
        {
            return Views::getView($id)[ 'name' ];
        }

        return null;
    }


    /**
     * Returns the description of a given view.
     *
     * @param $id
     *
     * @return null
     */
    public static function getViewDescription($id)
    {
        if (isset(Views::getView($id)[ 'description' ]))
        {
            return Views::getView($id)[ 'description' ];
        }

        return null;
    }
}