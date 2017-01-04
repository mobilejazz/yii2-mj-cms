<?php

use mobilejazz\yii2\cms\common\models\MenuItem;
use mobilejazz\yii2\cms\common\widgets\BoxPanel;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @param  array MenuItem[]
 */
function displayTree($tree)
{
    echo '<ul>';
    /** @var MenuItem $item */
    foreach ($tree as $item)
    {
        $title       = $item->getCurrenttitle();
        $modal_title = Yii::t('backend', 'Edit') . ': ' . $title;
        echo "<li id='$item[id]' parent='$item[parent]' children='$item[childs]' class='$item[class]'>";
        echo "<span>";
        if (isset($item->children))
        {
            echo Html::button(null, [
                'class' => 'treeMaximizer glyphicon glyphicon-minus',
                'style' => 'margin-right: 5px;',
            ]);
            echo "<p class='children'>$title" . "</p> ";
        }
        else
        {
            echo "<p class='empty'>$title" . "</p> ";
        }
        echo "<div class='btn-group pull-right' role='group'>";
        echo Html::a(Yii::t('backend', 'Edit'), false, [
            'data-value' => Url::to([ '/menu/menu-item', 'id' => $item->id ]),
            'label'      => $modal_title,
            'class'      => 'showModalButton glyphicon btn btn-link',
        ]);
        echo Html::a(null, [ '/menu/move-up', 'id' => $item->id ], [ 'class' => 'btn btn-link glyphicon glyphicon-arrow-up black' ]);
        echo Html::a(null, [ '/menu/move-down', 'id' => $item->id ], [ 'class' => 'btn btn-link glyphicon glyphicon-arrow-down black' ]);
        echo Html::a(null, [
            '/menu/delete-menu-item',
            'id'       => $item->id,
            'children' => boolval($item[ 'childs' ]),
        ], [
            'class'        => 'btn btn-link glyphicon glyphicon-trash red',
            'data-confirm' => Yii::t('backend', 'Are you sure to delete this item?'),
            'data-method'  => 'post',
        ]);
        echo "</div>";
        echo "</span>";
        if (isset($item->children))
        {
            displayTree($item[ 'children' ]);
        }
        echo "</li>";
    }
    echo '</ul>';
}

BoxPanel::begin([
    'title' => 'Menu Items',
])
?>
<div class="tree">
    <?php displayTree($model->getSortedMenuItems()); ?>
    <!-- flash message -->
    <?php if (\Yii::$app->session->getFlash('deleteMenuItemError') !== null) : ?>
        <span class="alert alert-warning alert-dismissible" role="alert" style="display: block;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            <?= \Yii::$app->session->getFlash('deleteMenuItemError') ?>
            </span>
    <?php endif; ?>
</div>
<?php
BoxPanel::end();

$script = <<< JS
// BACKEND MENU DISPLAY SYSTEM
$('.tree li:has(ul)').addClass('parent_li').find(' > span');
$('.tree li.parent_li > span > .treeMaximizer').on('click', function (e) {
    var children = $(this).parent('span').parent('li.parent_li').find(' > ul > li');
    if (children.is(":visible")) {
        children.hide('fast');
        $(this).addClass('glyphicon-plus').removeClass('glyphicon-minus');
    } else {
        children.show('fast');
        $(this).addClass('glyphicon-minus').removeClass('glyphicon-plus');
    }
    e.stopPropagation();
});
JS;

$this->registerJs($script);
?>
