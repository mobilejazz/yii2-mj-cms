<?php

use kartik\alert\Alert;
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title                     = Yii::t('backend', 'File Manager Settings');
$this->params[ 'breadcrumbs' ][] = [ 'label' => Yii::t('backend', 'File manager'), 'url' => [ 'default/index' ] ];
$this->params[ 'breadcrumbs' ][] = \Yii::t('backend', 'Settings');

\mobilejazz\yii2\cms\common\widgets\BoxPanel::begin([
    'display_header' => false,
]);
?>
<?php if (Yii::$app->session->getFlash('successResize')) : ?>
    <?= Alert::widget([
        'type'          => Alert::TYPE_SUCCESS,
        'title'         => Yii::t('backend', 'Thumbnails sizes has been resized successfully!'),
        'icon'          => 'glyphicon glyphicon-ok-sign',
        'body'          => Yii::t('backend', 'Do not forget every time you change thumbnails presets to make them resize.'),
        'showSeparator' => true,
    ]); ?>
<?php endif; ?>
    <p><?= Yii::t('backend', 'Now using next thumbnails presets') ?>:</p>
    <ul>
        <?php foreach ($this->context->module->thumbs as $preset) : ?>
            <li><strong><?= $preset[ 'name' ] ?>:</strong> <?= $preset[ 'size' ][ 0 ] . ' x ' . $preset[ 'size' ][ 1 ] ?></li>
        <?php endforeach; ?>
    </ul>
    <p><?= Yii::t('backend', 'If you change the thumbnails sizes, it is strongly recommended to make resize all thumbnails.') ?></p>
<?= Html::a(Yii::t('backend', 'Do resize thumbnails'), [ 'file/resize' ], [ 'class' => 'btn btn-danger' ]) ?>
<?php
\mobilejazz\yii2\cms\common\widgets\BoxPanel::end();