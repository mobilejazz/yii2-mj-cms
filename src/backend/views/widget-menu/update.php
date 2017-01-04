<?php

/* @var $this yii\web\View */
/* @var $model mobilejazz\yii2\cms\common\models\WidgetMenu */

$this->title                     = Yii::t('backend', 'Update Widget Menu') . ' ' . $model->title;
$this->params[ 'breadcrumbs' ][] = [ 'label' => 'Widget Menus', 'url' => [ 'index' ] ];
$this->params[ 'breadcrumbs' ][] = 'Update';
?>
<div class="widget-menu-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
