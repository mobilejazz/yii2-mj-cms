<?php

use mobilejazz\yii2\cms\backend\modules\filemanager\models\Mediafile;
use mobilejazz\yii2\cms\common\models\User;
use mobilejazz\yii2\cms\common\widgets\BoxPanel;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;

/**
 * @var $this         yii\web\View
 * @var $model        Mediafile
 * @var $dataProvider ActiveDataProvider
 */

$this->title                     = Yii::t('backend', 'File manager');
$this->params[ 'breadcrumbs' ][] = $this->title;
/** @var \mobilejazz\yii2\cms\common\models\User $user */
$user = \Yii::$app->user->getIdentity();
BoxPanel::begin([
    'display_header' => false,
])
?>
    <!-- Quick tools -->
    <div class="form-inline">
        <div class="form-group">
            <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('backend', 'Add Files'), [ '/filemanager/default/uploader' ],
                [ 'class' => 'btn btn-primary' ]) ?>
            <?php if ($user->role === User::ROLE_ADMIN): ?>
                <?= Html::a('<span class="glyphicon glyphicon-download"></span> ' . Yii::t('backend', 'Download All Media Files'),
                    [ '/filemanager/default/download-files' ], [ 'class' => 'btn btn-warning' ]) ?>
            <?php endif; ?>
        </div>
    </div>
    <!-- Spacer -->
    <hr>
<?= $this->render('_filemanager', [
    'model'        => $model,
    'dataProvider' => $dataProvider,
]); ?>
<?php BoxPanel::end();