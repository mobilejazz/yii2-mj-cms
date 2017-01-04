<?php

/**
 * @var $this  yii\web\View
 * @var $items \mobilejazz\yii2\cms\common\models\Menu
 */

use mobilejazz\yii2\cms\common\models\Menu;
use mobilejazz\yii2\cms\frontend\views\utils\ComplexNavUtils;

?>
<!-- Page title -->
<div class="introduction-text">
    <div class="row">
        <div class="small-12 columns">
            <h1><?= \Yii::t('app', 'SITEMAP'); ?></h1>
        </div>
    </div>
</div>

<!-- Sitemap -->
<div id="sitemap">
    <div class="row">
        <div class="small-12 medium-10 medium-offset-1 columns">
            <?php
            $menu = Menu::findOne([ 'key' => 'main-menu', ]);
            if (isset ($menu))
            {
                echo ComplexNavUtils::buildMenu($menu, true);
            }
            ?>
        </div>
    </div>
</div>
