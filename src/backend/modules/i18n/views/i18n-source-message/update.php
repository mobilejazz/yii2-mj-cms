<?php

/* @var $this yii\web\View */
/* @var $model mobilejazz\yii2\cms\backend\modules\i18n\models\I18nSourceMessage */

$this->title                     = Yii::t('backend', 'Update I18n Source Message') . " " . $model->id;
$this->params[ 'breadcrumbs' ][] = [ 'label' => 'I18n Source Messages', 'url' => [ 'index' ] ];
$this->params[ 'breadcrumbs' ][] = [ 'label' => $model->id, 'url' => [ 'view', 'id' => $model->id ] ];
$this->params[ 'breadcrumbs' ][] = Yii::t('backend', 'Update');
?>
<div class="i18n-source-message-update">

    <?php echo $this->render('_form', [
            'model' => $model,
        ]) ?>

</div>
