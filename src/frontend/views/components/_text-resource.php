<?php
use mobilejazz\yii2\cms\common\models\Components;
use mobilejazz\yii2\cms\common\models\ContentComponent;
use mobilejazz\yii2\cms\common\models\Fields;

/**
 * @var yii\web\View     $this
 * @var int              $size
 * @var ContentComponent $component
 * @var int              $column_size
 */

$fields = Components::getFieldsFromComponentAsArray($component);
if (!isset($column_size))
{
    $column_size = 4;
}
?>
<div class="small-12 medium-<?= $column_size ?> columns">
    <div class="recommended-item">
        <div class="recommended-item-equalizer" data-equalizer-watch>
            <!-- Image -->
            <?php if (strlen($fields[ Fields::FIELD_IMAGE ])): ?>
                <div class="recommended-item-image">
                    <img src="<?php echo $fields[ Fields::FIELD_IMAGE ]; ?>">
                </div>
            <?php endif; ?>

            <!-- Title -->
            <?php if (strlen($fields[ Fields::FIELD_TEXT_BOX ])): ?>
                <p class="recommended-item-title">
                    <?php echo $fields[ Fields::FIELD_TEXT_BOX ]; ?>
                </p>
            <?php endif; ?>

            <!-- Description -->
            <?php if (strlen($fields[ Fields::FIELD_TEXT_AREA ])): ?>
                <div class="recommended-item-description">
                    <?php echo $fields[ Fields::FIELD_TEXT_AREA ]; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Button -->
        <?php if (strlen($fields[ Fields::FIELD_LINK_URL ])): ?>
            <p><a href="<?php echo $fields[ Fields::FIELD_LINK_URL ]; ?>"
                  class="button <?php echo $fields[ Fields::FIELD_LINK_COLOR ]; ?>"
                    <?php if (!$fields[ Fields::FIELD_LINK_TARGET ])
                    {
                        echo 'target="_blank"';
                    } ?>
                >
                    <?php echo $fields[ Fields::FIELD_LINK_NAME ]; ?>
                </a></p>
        <?php endif; ?>
    </div>
</div>