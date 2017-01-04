<?php

/**
 * @var yii\web\View       $this
 * @var mobilejazz\yii2\cms\common\models\Menu $model
 */

use mobilejazz\yii2\cms\common\models\MenuItem;
use mobilejazz\yii2\cms\common\models\MenuItemTranslation;

$this->title                     = Yii::t('backend', 'Menu') . ' ' . $model->key . ': ' . Yii::t('backend', 'Edit');
$this->params[ 'breadcrumbs' ][] = [ 'label' => Yii::t('backend', 'Menus'), 'url' => [ 'index' ] ];
$this->params[ 'breadcrumbs' ][] = [ 'label' => (string) $model->key, 'url' => [ 'view', 'id' => $model->id ] ];
$this->params[ 'breadcrumbs' ][] = 'Edit';
?>
<!-- Menu -->
<div class="row">
    <!-- Menu creation form -->
    <?= $this->render('_menu_form', [
        'model' => $model,
    ]); ?>

    <!-- Menu Tree form -->
    <div class="col-xs-12 col-sm-6">
        <?= $this->render('_tree', [
            'model' => $model,
        ]); ?>
    </div>

    <!-- Add to Menu form -->
    <div class="col-xs-12 col-sm-3">
        <?php
        /** @var MenuItem $new_item */
        $new_item          = new MenuItem();
        $new_item->menu_id = $model->id;

        /** @var MenuItemTranslation $translation */
        $translation           = new MenuItemTranslation();
        $translation->language = \Yii::$app->language;
        ?>
        <?= $this->render('_menu_item_form', [
            'model'       => $new_item,
            'translation' => $translation,
        ]); ?>
    </div>
</div>