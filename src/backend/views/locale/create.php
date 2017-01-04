<?php

/**
 * @var yii\web\View         $this
 * @var mobilejazz\yii2\cms\common\models\Locale $model
 */

$this->title                     = Yii::t('backend', 'Create a new Language');
$this->params[ 'breadcrumbs' ][] = [ 'label' => 'Locales', 'url' => [ 'index' ] ];
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="box">
    <div class="box-body">
        <div class="giiant-crud locale-create">
            <?= $this->render('_form', [
                'model' => $model,
            ]); ?>

        </div>
    </div>
</div>
