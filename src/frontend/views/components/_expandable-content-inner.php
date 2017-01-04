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
<div
    class="expandable-element small-12 medium-<?= $column_size ?> columns <?= isset($fields[ Fields::FIELD_IS_OPEN ]) && $fields[ Fields::FIELD_IS_OPEN ] ? 'open' : '' ?>">
    <div class="expandable-element-header">
        <a id="<?= (str_replace(' ', '-', strtolower($fields[ Fields::FIELD_TITLE ]))) ?>"
           class=" expandable-expand-trigger"
           href="javascript:void(0)"><?= $fields[ Fields::FIELD_TITLE ]; ?></a>
    </div>

    <div class="expandable-element-body" style="<?= isset($fields[ Fields::FIELD_IS_OPEN ]) && boolval($fields[ Fields::FIELD_IS_OPEN ]) ? 'display:block;' : 'display:none;' ?>">
        <?php echo $fields[ Fields::FIELD_TEXT_AREA ]; ?>
    </div>
</div>