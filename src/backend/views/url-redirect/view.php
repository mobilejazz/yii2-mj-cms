<?php

use dmstr\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View              $this
 * @var mobilejazz\yii2\cms\common\models\UrlRedirect $model
 */

$this->title                     = Yii::t('backend', 'URL redirect from') . ' ' . $model->origin_slug . ' ' . Yii::t('backend',
        'URL redirect from') . ' ' . $model->destination_slug;
$this->params[ 'breadcrumbs' ][] = [ 'label' => Yii::t('backend', 'Url Redirects'), 'url' => [ 'index' ] ];
$this->params[ 'breadcrumbs' ][] = [ 'label' => (string) $model->id, 'url' => [ 'view', 'id' => $model->id ] ];
$this->params[ 'breadcrumbs' ][] = Yii::t('backend', 'View');
?>
<div class="box">
    <div class="box-body">
        <div class="giiant-crud url-redirect-view">
            <!-- menu buttons -->
            <p class='pull-left'>
                <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('backend', 'Edit'), [ 'update', 'id' => $model->id ],
                    [ 'class' => 'btn btn-info' ]) ?>
                <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('backend', 'New'), [ 'create' ],
                    [ 'class' => 'btn btn-success' ]) ?>
            </p>

            <p class="pull-right">
                <?= Html::a('<span class="glyphicon glyphicon-list"></span> ' . Yii::t('backend', 'List UrlRedirects'), [ 'index' ],
                    [ 'class' => 'btn btn-primary' ]) ?>
            </p>

            <div class="clearfix"></div>

            <!-- flash message -->
            <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
                <span class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                    <?= \Yii::$app->session->getFlash('deleteError') ?>
        </span>
            <?php endif; ?>


            <div class="panel-body">

                <?= DetailView::widget([
                    'model'      => $model,
                    'attributes' => [
                        'id',
                        'origin_slug',
                        'destination_slug',
                        [
                            'label'  => 'Test',
                            'value'  => "<a class='btn btn-primary'
                                    href='$model->origin_slug'
                                    data-toggle='tooltip'
                                    data-placement='top'
                                    title='Test this redirect'
                                    target='_blank'>Test</a>",
                            'format' => 'raw',
                        ],
                        'created_at:date',
                        'updated_at:date',
                    ],
                ]); ?>

                <hr/>

                <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ' . Yii::t('backend', 'Delete'), [ 'delete', 'id' => $model->id ], [
                    'class'        => 'btn btn-danger',
                    'data-confirm' => Yii::t('backend', 'Are you sure to delete this item?'),
                    'data-method'  => 'post',
                ]); ?>
            </div>
        </div>
    </div>
</div>