<?php
use mobilejazz\yii2\cms\backend\modules\filemanager\models\Mediafile;
use yii\helpers\Html;

/**
 * @var $this        yii\web\View
 * @var $model       Mediafile
 * @var $searchModel Mediafile
 */

?>
    <!-- Quick Tools -->
    <div class="form-inline">
        <div class="form-group">
            <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> ' . Yii::t('backend', 'Back to File Manager'),
                [ '/filemanager/default' ], [ 'class' => 'btn btn-primary' ]) ?>
        </div>
    </div>

    <!-- Spacer -->
    <hr>
<?= $this->render("_file_upload_ui", [
    'model' => $model,
]) ?>