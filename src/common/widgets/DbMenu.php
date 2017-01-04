<?php

namespace mobilejazz\yii2\cms\common\widget\base;

use mobilejazz\yii2\cms\common\models\WidgetMenu;
use yii;
use yii\base\InvalidConfigException;
use yii\widgets\Menu;

/**
 * Class DbMenu
 * Usage:
 * echo common\widgets\DbMenu::widget([
 *      'key'=>'stored-menu-key',
 *      ... other options from \yii\widgets\Menu
 * ])
 * @package common\widgets
 */
class DbMenu extends Menu
{

    /**
     * @var string Key to find menu model
     */
    public $key;


    public function init()
    {
        $cacheKey    = [
            WidgetMenu::className(),
            $this->key
        ];
        $this->items = Yii::$app->cache->get($cacheKey);
        if ($this->items === false)
        {
            if (!($model = WidgetMenu::findOne([ 'key' => $this->key, 'status' => WidgetMenu::STATUS_ACTIVE ])))
            {
                throw new InvalidConfigException;
            }
            $this->items = json_decode($model->items, true);
            Yii::$app->cache->set($cacheKey, $this->items, 60 * 60 * 24);
        }
    }
}
