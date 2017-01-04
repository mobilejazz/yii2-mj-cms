<?php

use mobilejazz\yii2\cms\common\models\WebFormSubmission;
use mobilejazz\yii2\cms\common\widgets\BoxPanel;
use trntv\aceeditor\AceEditor;
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

$this->title                     = Yii::t('backend', 'Submission for the Form: {form}', [ 'form' => $web_form_title ]);
$this->params[ 'breadcrumbs' ][] = [ 'label' => Yii::t('backend', 'Web Forms'), 'url' => [ '/web-form' ] ];
$this->params[ 'breadcrumbs' ][] = [ 'label' => $web_form_title, 'url' => [ '/web-form/update', 'id' => $web_form->id ] ];
$this->params[ 'breadcrumbs' ][] = Yii::t('app', 'View');
?>
<div class="row">
    <div class="col-xs-12">
        <?php
        // ============================
        // WEB FORM SUBMISSION RELATED
        // ============================
        BoxPanel::begin([
            'title' => Yii::t('backend', 'Form Details'),
        ]);
        ?>
        <h4><?= Yii::t('backend', 'Submission details') ?></h4>
        <p><?= Yii::t('backend',
                'The following are the specific details of this particular submission (which might change from submission to submission)') ?></p>

        <h4><?= Yii::t('backend', 'Form Description') ?></h4>
        <?= $model->decodedDescription() ?>

        <h4><?= Yii::t('backend', 'Mails to which the submission was sent') . ':' ?></h4>
        <?= BaseHtml::ol($model->decodedMails()) ?>

        <h4><?= Yii::t('backend', 'Thank you message') ?></h4>
        <?= $model->decodedMessage() ?>

        <h4><?= Yii::t('backend', 'Answers') ?></h4>
        <p><?= Yii::t('backend', 'Answers given in this particular submission') ?></p>
        <?php
        /**
         * The Data Provider gives us a perfect way to display arrays in Grids.
         */
        $dataProvider = new ArrayDataProvider([
            'key'       => 'id',
            'allModels' => json_decode($model->fields, true),
            'sort'      => [
                'attributes' => [ 'field_name', 'field_type', 'user_response', 'placeholder', 'hint' ],
            ],
        ]);

        echo GridView::widget([
            'layout'           => '{items}{pager}',
            'dataProvider'     => $dataProvider,
            'filterModel'      => false,
            'tableOptions'     => [ 'class' => 'table table-striped' ],
            'headerRowOptions' => [ 'class' => '' ],
        ]);
        ?>
        <h4><?= Yii::t('backend', 'Script run on the client after submission') ?></h4>
        <?= AceEditor::widget([
            'model'     => $model,
            'attribute' => 'script',
            'mode'      => 'javascript',
            'theme'     => 'github',
        ]); ?>

        <?php
        BoxPanel::end();
        ?>
    </div>
</div>