<?php

/**
 * @var yii\web\View         $this
 * @var mobilejazz\yii2\cms\common\models\Locale $model
 */

$this->title                     = $model->label . ' locale: Edit';
$this->params[ 'breadcrumbs' ][] = [ 'label' => 'Locales', 'url' => [ 'index' ] ];
$this->params[ 'breadcrumbs' ][] = [ 'label' => $model->label, 'url' => [ 'view', 'id' => $model->id, 'lang' => $model->lang ] ];
$this->params[ 'breadcrumbs' ][] = Yii::t('backend', 'Edit');
?>
<div class="box">
    <div class="box-body">
        <div class="giiant-crud locale-update">
            <?php echo $this->render('_form', [
                'model' => $model,
            ]); ?>

        </div>
    </div>
</div>