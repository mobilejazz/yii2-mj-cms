<?php

/* @var $this yii\web\View */
/* @var $model mobilejazz\yii2\cms\backend\modules\i18n\models\I18nMessage */

$this->title                     = 'Update I18n Message ' . $model->id;
$this->params[ 'breadcrumbs' ][] = [ 'label' => Yii::t('backend', 'I18n Messages'), 'url' => [ 'index' ] ];
$this->params[ 'breadcrumbs' ][] = [ 'label' => $model->id, 'url' => [ 'view', 'id' => $model->id, 'language' => $model->language ] ];
$this->params[ 'breadcrumbs' ][] = Yii::t('backend', 'Update');
?>
<div class="box">
    <div class="box-body">
        <div class="i18n-message-update">
            <?php echo $this->render('_form', [
                    'model' => $model,
                ]) ?>
        </div>
    </div>
</div>

