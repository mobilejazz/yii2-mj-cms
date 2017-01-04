<?php
/* @var $this yii\web\View */
/* @var $model mobilejazz\yii2\cms\backend\modules\i18n\models\I18nMessage */

$this->title                     = Yii::t('backend', 'Create I18n Message');
$this->params[ 'breadcrumbs' ][] = [ 'label' => 'I18n Messages', 'url' => [ 'index' ] ];
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="box">
    <div class="box-body">
        <div class="i18n-message-create">
            <?php echo $this->render('_form', [
                    'model' => $model,
                ]) ?>
        </div>
    </div>
</div>
