<?php

/**
 * @var yii\web\View              $this
 * @var mobilejazz\yii2\cms\common\models\UrlRedirect $model
 */

$this->title                     = Yii::t('backend', 'Create a new Redirect');
$this->params[ 'breadcrumbs' ][] = [ 'label' => 'Url Redirects', 'url' => [ 'index' ] ];
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="box">
    <div class="box-body">
        <?= $this->render('_form', [
            'model' => $model,
        ]); ?>
    </div>
</div>