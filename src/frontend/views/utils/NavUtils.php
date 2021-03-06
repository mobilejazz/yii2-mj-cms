<?php

namespace mobilejazz\yii2\cms\frontend\views\utils;

use Exception;
use mobilejazz\yii2\cms\common\models\ContentSource;
use mobilejazz\yii2\cms\common\models\Menu;
use mobilejazz\yii2\cms\common\models\MenuItem;
use yii;

class NavUtils
{

    /**
     * @param Menu|MenuItem $menu
     *
     * @return array
     * @throws Exception
     */
    public static function buildMenu($menu)
    {

        if ($menu == null)
        {
            return [];
        }

        if ($menu instanceof Menu)
        {

            $items = [];

            foreach ($menu->getSortedMenuItems() as $menuItem)
            {
                $items[] = self::buildMenu($menuItem, false);
            }

            return $items;

        }
        else if ($menu instanceof MenuItem)
        {

            $lang = Yii::$app->language;

            $menu_item_translation = $menu->getCurrentTranslation($lang);

            $label = $menu_item_translation->title;
            $url = $menu_item_translation->link;
            $css_class = $menu->class;

            if (isset($menu->content_id) && $menu->content_id != null)
            {
                $content = ContentSource::findOne([ 'id' => $menu->content_id ]);

                /** @var string $slug */
                $slug = $content->getCurrentContentSlug($lang);
                $url  = Yii::$app->frontendUrlManager->createBaseUrl('cmsfrontend/site/content', [
                    'lang' => Yii::$app->language,
                    'slug' => $slug,
                ]);

            }

            $result = [
                'label' => $label,
                'url'   => $url,
                'class'   => $css_class,
                'items' => []
            ];

            if ($menu->children)
            {
                foreach ($menu->children as $child)
                {
                    $result[ 'items' ][] = self::buildMenu($child, false);
                }
            }

            return $result;
        }
        else
        {
            var_dump($menu);
            $className = $menu::className();
            throw new Exception("Unexpected type: $className");
        }

    }

}