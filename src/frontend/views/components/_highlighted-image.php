<?php

use mobilejazz\yii2\cms\common\models\Components;
use mobilejazz\yii2\cms\common\models\ContentComponent;
use mobilejazz\yii2\cms\common\models\Fields;

/**
 * @var yii\web\View     $this
 * @var int              $size
 * @var ContentComponent $component
 */
$fields = Components::getFieldsFromComponentAsArray($component);
?>
<div class="highlighted-box row <?= $fields[ Fields::FIELD_CMS_COLOR_PALETTE_BACKGROUND ]; ?>">
    <img class="highlighted-image hide-for-small" src="<?php echo $fields[ Fields::FIELD_IMAGE ]; ?>">
    <h1 class="highlighted-text"><?= $fields[ Fields::FIELD_TITLE_BOLD ]; ?><br/>
        <span class="text-light"><?= $fields[ Fields::FIELD_TITLE_NON_BOLD ]; ?></span>
    </h1>
    <div class="highlighted-frame <?= $fields[ Fields::FIELD_FRAME_COLOR ]; ?>"></div>
</div>