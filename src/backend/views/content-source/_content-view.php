<?php

use mobilejazz\yii2\cms\common\models\ContentComponent;
use mobilejazz\yii2\cms\common\models\ContentSource;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\web\View;

?>
<div class="form-group form-inline ">
    <h4 style="display: inline-block;"><?= \Yii::t('backend', 'Content'); ?></h4>
    <!-- Add new component Button. -->
    <?= Html::a('<span class="fa fa-plus"></span> ' . Yii::t('backend', 'Add Component'), false, [
        'data-value' => Url::to([
            'add-component',
            'id'    => $model->id,
            'order' => ContentComponent::getMaxOrder($components[ 0 ]),
        ]),
        'label'      => Yii::t('backend', 'Add new Component'),
        'class'      => 'showModalButton btn btn-primary btn-sm pull-right do-not-go-full-width',
        'style'      => 'margin-left: 20px;',
    ]); ?>

    <!-- Add form to this Content Source -->
    <?= Html::a('<span class="fa fa-plus"></span> ' . Yii::t('backend', 'Add Form'), false, [
        'data-value' => Url::to([
            'add-form',
            'id'    => $model->id,
            'order' => ContentComponent::getMaxOrder($components[ 0 ]),
        ]),
        'label'      => Yii::t('backend', 'Add an existing Form to this Content'),
        'class'      => 'showModalButton btn btn-primary btn-sm pull-right do-not-go-full-width',
        'style'      => 'margin-left: 3px',
    ]); ?>
</div>
<div class="sortable-list">
    <?php
    echo $this->render("_single-component", [
        'components'   => $components,
        'field_errors' => $field_errors,
    ]);
    ?>
</div>
