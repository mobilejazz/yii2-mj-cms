<?php

use yii\bootstrap\Modal;

/**
 * @var $this yii\web\View
 */
Modal::begin([
    'options'       => [
        'id'       => 'modal',
        'tabindex' => false,
        'class'    => '',
    ],
    'headerOptions' => [ 'id' => 'modalHeader' ],
    'clientOptions' => [
        'backdrop' => 'true',
        'keyboard' => true,
    ],
]);
echo "<div class='loader-activator'>
            <div style='text-align:center; font-size: 33px;'>
                <i class='fa fa-refresh fa-spin'></i> " . Yii::t('backend', 'Loading...') . "
            </div>
     </div>";
echo "<div id='modalContent'></div>";
Modal::end();
$this->beginContent('@mobilejazz/yii2/cms/backend/views/layouts/common.php');
echo $content;

$this->endContent();