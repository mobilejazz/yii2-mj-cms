<?php

use mobilejazz\yii2\cms\common\models\WebFormSubmission;
use yii\bootstrap\BaseHtml;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\web\View;

/**
 * @var View              $this
 * @var WebFormSubmission $model
 */
$copyParams     = $model->attributes;
$web_form       = $model->webForm;
$web_form_title = $web_form->getTitle();
$model->loadData();
$fields = $model->fields;

// ============================
// WEB FORM SUBMISSION RELATED
// ============================
?>
    <h2><?= Yii::t('app', 'A user has submitted a new form with the name: {form}', [ 'form' => $web_form_title, ]) ?></h2>
    <h4><?= Yii::t('app', 'Submission details') ?></h4>
    <p><?= Yii::t('app',
            'The following are the specific details of this particular submission (which might change from submission to submission)') ?></p>

    <h4><?= Yii::t('app', 'Form Description') ?></h4>
<?= $model->decodedDescription() ?>

    <h4><?= Yii::t('app', 'Mails to which the submission was sent') . ':' ?></h4>
<?= BaseHtml::ol($model->decodedMails()) ?>

    <h4><?= Yii::t('app', 'Thank you message') ?></h4>
<?= $model->decodedMessage() ?>

    <h4><?= Yii::t('app', 'Answers') ?></h4>
    <p><?= Yii::t('app', 'Answers given in this particular submission') ?></p>
<?php
/**
 * The Data Provider gives us a perfect way to display arrays in Grids.
 */
$dataProvider = new ArrayDataProvider([
    'key'       => 'id',
    'allModels' => json_decode($fields, true),
]);

echo GridView::widget([
    'layout'           => '{items}{pager}',
    'dataProvider'     => $dataProvider,
    'filterModel'      => false,
    'tableOptions'     => [ 'class' => 'table table-striped' ],
    'headerRowOptions' => [ 'class' => '' ],
]);
?>