<?php
use mobilejazz\yii2\cms\common\models\Menu;
use mobilejazz\yii2\cms\frontend\views\utils\ComplexNavUtils;

/**
 * Created by IntelliJ IDEA.
 * * User: polbatllo
 * Date: 24/11/15
 * Time: 17:23
 * @var yii\web\View           $this
 * @var common\models\Menu     $model
 * @var yii\widgets\ActiveForm $form
 */

?>
<div class="tree">
    <nav id="main-menu">
        <div class="menu-container">
            <?php
            $menu = Menu::findOne([ 'key' => 'main-menu', ]);
            if (isset($menu))
            {
                echo ComplexNavUtils::buildMenu($menu, true);
            }
            ?>
        </div>
    </nav>
</div>