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
        <div class="small-12 columns">
            <?php echo $fields[ Fields::FIELD_TEXT_AREA ]; ?>
        </div>
    </div>
</div>