<?php

/* @var $this yii\web\View */
use mobilejazz\yii2\cms\backend\modules\i18n\models\search\I18nMessageSearch;
use mobilejazz\yii2\cms\backend\widgets\ExpandedGridView;
use mobilejazz\yii2\cms\common\models\Locale;
use kartik\export\ExportMenu;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $searchModel  I18nMessageSearch
 * @var $dataProvider ActiveDataProvider
 */

$this->title                     = 'Translations';
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="box">
    <div class="box-body">
        <?php echo ExpandedGridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'bulk_actions' => false,
            'create'       => Html::a('<i class="fa fa-refresh icon-margin small"></i> ' . Yii::t('backend', 'Scan for new messages '),
                    [ 'scan-for-new-messages' ], [
                        'class'        => 'btn btn-primary',
                        'data-confirm' => Yii::t('backend', 'This could take a while, please wait until the process has ended.'),
                    ]) . ' ' . ExportMenu::widget([
                    'dataProvider'          => $dataProvider,
                    'fontAwesome'           => true,
                    'columns'               => [
                        'language',
                        'category',
                        'sourceMessage',
                        'translation',
                    ],
                    'columnSelectorOptions' => [
                        'label' => ' ' . Yii::t('backend', 'Exported Rows') . ' ',
                        'class' => 'btn btn-default dropdown-toggle',
                        'title' => '',
                    ],
                    'encoding'              => 'UTF-16',
                    'dropdownOptions'       => [
                        'label'   => ' ' . Yii::t('backend', 'Export') . ' ',
                        'class'   => 'btn btn-default dropdown-toggle',
                        'options' => [
                            'title' => false,
                        ],
                    ],
                    'exportConfig'          => [
                        ExportMenu::FORMAT_HTML    => [
                            'filename' => \Yii::t('backend', 'translations-to-{language}', [ 'language' => Locale::getCurrent(), ]),
                            'options'  => [ 'title' => false, ],
                        ],
                        ExportMenu::FORMAT_CSV     => [
                            'filename' => \Yii::t('backend', 'translations-to-{language}', [ 'language' => Locale::getCurrent(), ]),
                            'options'  => [ 'title' => false ],
                        ],
                        ExportMenu::FORMAT_TEXT    => [
                            'filename' => \Yii::t('backend', 'translations-to-{language}', [ 'language' => Locale::getCurrent(), ]),
                            'options'  => [ 'title' => false, ],
                        ],
                        ExportMenu::FORMAT_PDF     => false,
                        ExportMenu::FORMAT_EXCEL   => false,
                        ExportMenu::FORMAT_EXCEL_X => [
                            'filename' => \Yii::t('backend', 'translations-to-{language}', [ 'language' => Locale::getCurrent(), ]),
                            'label'    => Yii::t('backend', 'Excel'),
                            'options'  => [ 'title' => false ],
                        ],
                    ],
                ]) . ' ' . Html::a('<i class="fa fa-refresh icon-margin small"></i>' . \Yii::t('backend', 'Filter Missing Translations'),
                    [ 'missing-translations' ], [ 'class' => 'btn btn-danger', ]),
            'columns'      => [
                // 'language',
                // TODO (Pol) RE-ENABLE IF WE WANT TO EDIT DIFFERENT CATEGORIES OTHER THAN MED. ALSO REMEMBER TO RE-ENABLE IN THE SEARCH MODEL THE OTHER CATEGORIES.
                [
                    'attribute' => 'category',
                    'filter'    => $categories,
                ],
                'sourceMessage:ntext',
                [
                    'attribute'     => 'translation',
                    'enableSorting' => false,
                    'format'        => 'ntext',
                ],
                [
                    'class'          => 'yii\grid\ActionColumn',
                    'header'         => Yii::t('backend', 'Edit'),
                    'template'       => '{update}',
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
                            return Html::a('Edit', false, [
                                'class'      => 'showModalButton',
                                'data-value' => $url,
                                'label'      => Yii::t('backend', 'Translate String'),
                                'style'      => 'cursor: pointer;',
                            ]);
                        },
                    ],
                    'contentOptions' => [ 'nowrap' => 'nowrap' ],
                ],
            ],
        ]); ?>
    </div>
</div>
<?php $script = <<< JS
$(".ui-button").removeClass("ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only").attr('title', '');
JS;
$this->registerJs($script);
?>
