<?php
/* @var $this yii\web\View */
/* @var $model mobilejazz\yii2\cms\common\models\WidgetMenu */

$this->title                     = Yii::t('backend', 'Create Widget Menu');
$this->params[ 'breadcrumbs' ][] = [ 'label' => Yii::t('backend', 'Widget Menus'), 'url' => [ 'index' ] ];
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="widget-menu-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
