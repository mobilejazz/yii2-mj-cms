<?php

use mobilejazz\yii2\cms\backend\modules\filemanager\assets\FilemanagerAsset;
use mobilejazz\yii2\cms\backend\modules\filemanager\models\Mediafile;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

/**
 * @var $this         yii\web\View
 * @var $model        Mediafile
 * @var $searchModel  Mediafile
 * @var $dataProvider ActiveDataProvider
 */
$this->params[ 'moduleBundle' ] = FilemanagerAsset::register($this);
?>
<div id="filemanager" data-url-info="<?= Url::to([ 'default/info' ]) ?>">
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'layout'       => '<div class="items">{items}</div>{pager}',
        'itemOptions'  => [ 'class' => 'item' ],
        'itemView'     => function ($model, $key, $index, $widget)
        {
            /** @var Mediafile $model */
            if (!$model->isImage())
            {
                return Html::a(Html::img(Yii::getAlias('@web/img/file.png')) . '<span class="checked fa fa-check"></span><div class="after"></div>',
                    '#mediafile', [ 'data-key' => $key ]);
            }
            else if (file_exists(substr($model->getDefaultThumbUrl($this->params[ 'moduleBundle' ]->baseUrl), 1)))
            {
                return Html::a(Html::img(Yii::getAlias('@web') . $model->getDefaultThumbUrl($this->params[ 'moduleBundle' ]->baseUrl)) . '<span class="checked fa fa-check"></span><div class="after"></div>',
                    '#mediafile', [ 'data-key' => $key ]);
            }
            else
            {
                return null;
            }
        },
    ]) ?>

    <div class="dashboard">
        <div id="fileinfo">
            <div class="callout callout-info">
                <h4><?= \Yii::t('backend', 'Information'); ?></h4>
                <p><?= \Yii::t('backend', 'Select one image to display its properties'); ?></p>
            </div>

        </div>
    </div>
</div>
