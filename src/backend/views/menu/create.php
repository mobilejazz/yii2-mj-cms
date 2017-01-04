<?php

use yii\helpers\Html;

/**
 * @var yii\web\View       $this
 * @var mobilejazz\yii2\cms\common\models\Menu $model
 */

$this->title                     = Yii::t('backend', 'Create');
$this->params[ 'breadcrumbs' ][] = [ 'label' => 'Menus', 'url' => [ 'index' ] ];
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="box">
    <div class="box-body">
        <div class="giiant-crud menu-create">

            <p class="pull-left">
                <?= Html::a(Yii::t('backend', 'Cancel'), \yii\helpers\Url::previous(), [ 'class' => 'btn btn-primary' ]) ?>
            </p>

            <div class="clearfix"></div>

            <?= $this->render('_menu_form', [
                'model' => $model,
            ]); ?>

        </div>

    </div>
</div>
