<?php

use kartik\export\ExportMenu;
use mobilejazz\yii2\cms\common\widgets\BoxPanel;
use yii\data\ArrayDataProvider;

/**
 * @var yii\web\View      $this
 * @var ArrayDataProvider $data
 * @var array             $columns
 */
if (!Yii::$app->request->isAjax)
{
    BoxPanel::begin([
        'title' => Yii::t('backend', 'Export Web Form Submission'),
    ]);
}

/** @var ExportMenu $widget */
$widget = new ExportMenu([
    'dataProvider' => $dataProvider,
]);
echo $widget->run();
$script = <<< JS
$(".ui-button").removeClass("ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only").attr('title', '');
JS;
$this->registerJs($script);