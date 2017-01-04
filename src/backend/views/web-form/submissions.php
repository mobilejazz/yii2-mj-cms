<?php

use kartik\export\ExportMenu;
use mobilejazz\yii2\cms\backend\models\search\WebFormSubmissionSearch;
use mobilejazz\yii2\cms\backend\widgets\ExpandedGridView;
use mobilejazz\yii2\cms\common\models\WebFormSubmission;
use mobilejazz\yii2\cms\common\widgets\BoxPanel;
use yii\base\DynamicModel;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\widgets\Pjax;

/**
 * @var yii\web\View            $this
 * @var ActiveDataProvider      $dataProvider
 * @var WebFormSubmissionSearch $searchModel
 * @var int                     $id
 */

$this->title                     = Yii::t('backend', 'Web Form Submissions');
$this->params[ 'breadcrumbs' ][] = [ 'label' => Yii::t('backend', 'Web Forms'), 'url' => [ '/web-form' ] ];
$this->params[ 'breadcrumbs' ][] = $this->title;

$allSubmissions = WebFormSubmission::find()
                                   ->where([
                                       'web_form' => $id,
                                   ])
                                   ->all();
$allExport      = ExportMenu::widget([
    'dataProvider'       => WebFormSubmission::getDataToArray($id, $allSubmissions),
    'fontAwesome'        => true,
    'showColumnSelector' => false,
    'encoding'           => 'UTF-16',
    'dropdownOptions'    => [
        'label'   => ' ' . Yii::t('backend', 'Export All') . ' ',
        'class'   => 'btn btn-default dropdown-toggle',
        'options' => [
            'title' => false,
        ],
    ],
    'exportConfig'       => [
        ExportMenu::FORMAT_HTML    => [ 'options' => [ 'title' => false, ], ],
        ExportMenu::FORMAT_CSV     => [ 'options' => [ 'title' => false ], ],
        ExportMenu::FORMAT_TEXT    => [ 'options' => [ 'title' => false, ], ],
        ExportMenu::FORMAT_PDF     => false,
        ExportMenu::FORMAT_EXCEL   => false,
        ExportMenu::FORMAT_EXCEL_X => false,
    ],
    'onExportCompleted'  => function ($dataProvider)
    {
        /** @var DynamicModel[] $submissions */
        $submissions = $dataProvider->allModels;
        $web_form    = 0;
        foreach ($submissions as $submission)
        {
            /** @var WebFormSubmission $submission */
            $submission           = WebFormSubmission::findOne([ 'id' => $submission->id, ]);
            $submission->exported = 1;
            $submission->save();
            $web_form = $submission->web_form;
        }

        return \Yii::$app->response->redirect([
            '/web-form/submissions/?WebFormSubmissionSearch[web_form]=' . $web_form,
        ]);
    },
]);

$allNonExportedSubmissions = WebFormSubmission::find()
                                              ->where([
                                                  'web_form' => $id,
                                                  'exported' => 0,
                                              ])
                                              ->all();

$allNonExported = '';
if (!empty($allNonExportedSubmissions))
{
    $allNonExported = ExportMenu::widget([
        'dataProvider'       => WebFormSubmission::getDataToArray($id, $allNonExportedSubmissions),
        'fontAwesome'        => true,
        'showColumnSelector' => false,
        'encoding'           => 'UTF-16',
        'dropdownOptions'    => [
            'label'   => ' ' . Yii::t('backend', 'Export Non Exported') . ' ',
            'class'   => 'btn btn-default dropdown-toggle',
            'options' => [
                'title' => false,
            ],
        ],
        'exportConfig'       => [
            ExportMenu::FORMAT_HTML    => [ 'options' => [ 'title' => false, ], ],
            ExportMenu::FORMAT_CSV     => [ 'options' => [ 'title' => false ], ],
            ExportMenu::FORMAT_TEXT    => [ 'options' => [ 'title' => false, ], ],
            ExportMenu::FORMAT_PDF     => false,
            ExportMenu::FORMAT_EXCEL   => false,
            ExportMenu::FORMAT_EXCEL_X => false,
        ],
        'onExportCompleted'  => function ($dataProvider)
        {
            /** @var DynamicModel[] $submissions */
            $submissions = $dataProvider->allModels;
            $web_form    = 0;
            foreach ($submissions as $submission)
            {
                /** @var WebFormSubmission $submission */
                $submission           = WebFormSubmission::findOne([ 'id' => $submission->id, ]);
                $submission->exported = 1;
                $submission->save();
                $web_form = $submission->web_form;
            }

            return \Yii::$app->response->redirect([
                '/web-form/submissions/?WebFormSubmissionSearch[web_form]=' . $web_form,
            ]);
        },
    ]);
}

BoxPanel::begin([
    'display_header' => false,
]);
Pjax::begin([
    'id'                 => 'pjax-web-form-submission-overview',
    'enableReplaceState' => false,
    'linkSelector'       => '#pjax-main ul.pagination a, th a',
    'clientOptions'      => [ 'pjax:success' => 'function(){alert("yo")}' ],
]);

echo ExpandedGridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'bulk_actions' => [
        ''       => \Yii::t('backend', 'Bulk Actions'),
        'delete' => \Yii::t('backend', 'Delete')
    ],
    'create'       => $allExport . ' ' . $allNonExported,
    'columns'      => [
        [
            'class' => 'yii\grid\CheckboxColumn',
        ],
        [
            'attribute' => Yii::t('backend', 'Language'),
            'value'     => function ($model)
            {
                /** @var WebFormSubmission $model */
                return $model->decodedLanguage();
            },
            'filter'    => false,
            'format'    => 'raw',
        ],
        [
            'attribute' => Yii::t('backend', 'Script'),
            'value'     => function ($model)
            {
                /** @var WebFormSubmission $model */
                return $model->decodedScriptBoolean();
            },
            'filter'    => false,
            'format'    => 'boolean',
        ],
        [
            'attribute' => Yii::t('backend', 'Mails Sent'),
            'value'     => function ($model)
            {
                /** @var WebFormSubmission $model */
                return $model->decodedMailCount();
            },
            'filter'    => false,
            'format'    => 'raw',
        ],
        [
            'attribute' => 'exported',
            'format'    => 'boolean',
        ],
        [
            'attribute' => 'created_at',
            'label'     => Yii::t('backend', 'Date'),
            'value'     => function ($model)
            {
                /** @var WebFormSubmission $model */
                return Yii::t("backend", "Submitted on ") . date("d/m/y H:i", $model->created_at);
            },
            'filter'    => false,
        ],
        [
            'class'          => 'yii\grid\ActionColumn',
            'header'         => Yii::t('backend', 'Action'),
            'template'       => '{view} | {delete}',
            'urlCreator'     => function ($action, $model, $key, $index)
            {
                // using the column name as key, not mapping to 'id' like the standard generator
                $params      = is_array($key) ? $key : [ $model->primaryKey()[ 0 ] => (string) $key ];
                $params[ 0 ] = 'web-form-submission/' . $action;

                return Url::toRoute($params);
            },
            'buttons'        => [
                'view'   => function ($url, $model, $key)
                {
                    return Html::a(Yii::t('backend', 'View'), $url);
                },
                'delete' => function ($url, $model, $key)
                {
                    return Html::a(Yii::t('backend', 'Delete'), $url, [
                        'data' => [
                            'confirm' => Yii::t('backend', 'Are you sure that you want to delete this Submission? '),
                        ],
                    ]);
                },
            ],
            'contentOptions' => [ 'nowrap' => 'nowrap' ],
        ],
    ],
]);

Pjax::end();
BoxPanel::end();
$script = <<< JS
$(".ui-button").removeClass("ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only").attr('title', '');
JS;
$this->registerJs($script);

