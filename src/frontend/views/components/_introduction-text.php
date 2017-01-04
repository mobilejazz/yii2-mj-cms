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
<div class="introduction-text">
    <div class="row">
        <div class="small-12 large-4 columns">
            <h1 class="<?= $fields[ Fields::FIELD_CMS_COLOR_PALETTE_TEXT ] ?>"><?= $fields[ Fields::FIELD_TITLE_BOLD ]; ?> <span class="text-light"><?= $fields[ Fields::FIELD_TITLE_NON_BOLD ]; ?></span></h1>
        </div>

        <div class="small-12 large-8 columns">
            <?= $fields[ Fields::FIELD_TEXT_AREA ]; ?>
        </div>
    </div>
</div>