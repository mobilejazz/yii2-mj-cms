<?php
use mobilejazz\yii2\cms\backend\modules\filemanager\models\Mediafile;
use mobilejazz\yii2\cms\common\widgets\BoxPanel;

/**
 * @var $this        yii\web\View
 * @var $model       Mediafile
 * @var $searchModel Mediafile
 */

$this->title                     = Yii::t('backend', 'Uploader');
$this->params[ 'breadcrumbs' ][] = $this->title;
BoxPanel::begin([
    'display_header' => false,
]);

echo $this->render('uploadmanager', [ 'model' => new Mediafile() ]);
BoxPanel::end();