<?php

use mobilejazz\yii2\cms\backend\widgets\LinkPager;
use mobilejazz\yii2\cms\common\models\Locale;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View                       $this
 * @var yii\data\ActiveDataProvider        $dataProvider
 * @var mobilejazz\yii2\cms\backend\models\search\LocaleSearch $searchModel
 */

$this->title                     = 'Locales';
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="box">
    <div class="box-body">
        <?php \yii\widgets\Pjax::begin([
            'id'                 => 'pjax-main',
            'enableReplaceState' => false,
            'linkSelector'       => '#pjax-main ul.pagination a, th a',
            'clientOptions'      => [ 'pjax:success' => 'function(){alert("yo")}' ],
        ])
        ?>
        <!-- Quick tools -->
        <div class="form-inline">
            <div class="form-group">
                <?= Html::a('<i class="fa fa-plus icon-margin small"></i> ' . Yii::t('backend', 'Add New'), [ 'create' ],
                    [ 'class' => 'btn btn-primary' ]) ?>
            </div>
        </div>
        <p></p>

        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-ban"></i> <?= Yii::t('backend', 'Alert!') ?></h4>
            <?= Yii::t('backend', 'When removing languages, keep in mind that any translation you have made will be deleted') ?>.
        </div>

        <!-- flash message -->
        <?php if (\Yii::$app->session->getFlash('error') !== null) : ?>
            <span class="alert alert-danger alert-dismissible" role="alert" style="display: block; margin-top: 15px;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <?= \Yii::$app->session->getFlash('error') ?>
        </span>
        <?php endif; ?>

        <?php if (\Yii::$app->session->getFlash('success') !== null) : ?>
            <span class="alert alert-success alert-dismissible" role="alert" style="display: block; margin-top: 15px;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <?= \Yii::$app->session->getFlash('success') ?>
        </span>
        <?php endif; ?>
        <?= GridView::widget([
            'layout'           => '{pager}{items}',
            'dataProvider'     => $dataProvider,
            'pager'            => [
                'class'   => LinkPager::className(),
                'options' => [
                    'class' => 'pagination',
                    'style' => 'display: inline',
                ],
            ],
            'tableOptions'     => [ 'class' => 'table table-striped add-top-margin' ],
            'headerRowOptions' => [ 'class' => '' ],
            'columns'          => [
                [
                    'attribute' => 'lang',
                    'label'     => \Yii::t('backend', 'Language'),
                    'filter'    => Locale::getAllLocales(),
                ],
                'country_code',
                [
                    'attribute' => 'label',
                    'label'     => \Yii::t('backend', 'Identifier'),
                    'filter'    => Locale::getAllLabels(),
                ],
                'used:boolean',
                'default:boolean',
                'rtl:boolean',
                [
                    'class'          => 'yii\grid\ActionColumn',
                    'header'         => Yii::t('backend', 'Action'),
                    'template'       => '{update} | {delete}',
                    'urlCreator'     => function ($action, $model, $key, $index)
                    {
                        // using the column name as key, not mapping to 'id' like the standard generator
                        $params      = is_array($key) ? $key : [ $model->primaryKey()[ 0 ] => (string) $key ];
                        $params[ 0 ] = \Yii::$app->controller->id ? \Yii::$app->controller->id . '/' . $action : $action;

                        return Url::toRoute($params);
                    },
                    'buttons'        => [
                        'update' => function ($url, $model, $key)
                        {
                            return Html::a('Edit', $url);
                        },
                        'delete' => function ($url, $model, $key)
                        {
                            return Html::a('Delete', $url, [
                                'data' => [
                                    'confirm' => Yii::t('backend', 'Are you sure you want to delete this language?'),
                                    'method'  => 'post',
                                ],
                            ]);
                        },
                    ],
                    'contentOptions' => [ 'nowrap' => 'nowrap' ],
                ],
            ],
        ]); ?>
        <?php \yii\widgets\Pjax::end() ?>
    </div>
</div>
