<?php

/**
 * @var yii\web\View              $this
 * @var mobilejazz\yii2\cms\common\models\UrlRedirect $model
 */

$this->title                     = Yii::t('backend', 'Edit URL redirect from') . ' ' . $model->origin_slug . '' . Yii::t('backend',
        'to') . ' ' . $model->destination_slug;
$this->params[ 'breadcrumbs' ][] = [ 'label' => Yii::t('backend', 'Url Redirects'), 'url' => [ 'index' ] ];
$this->params[ 'breadcrumbs' ][] = [ 'label' => (string) $model->id, 'url' => [ 'view', 'id' => $model->id ] ];
$this->params[ 'breadcrumbs' ][] = 'Edit';
?>
<div class="box">
    <div class="box-body">
        <?php echo $this->render('_form', [
            'model' => $model,
        ]); ?>

    </div>
</div>