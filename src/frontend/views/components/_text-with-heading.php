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

<?php if (!$column_size) : ?>
<div class="text-with-heading">
    <div class="row">
        <div class="small-12 medium-10 medium-offset-1 columns">
            <div class="row">
                <?php endif; ?>

                <div class="small-12 <?php echo $column_size ? 'medium-' . $column_size : ''; ?> columns">
                    <?php if (strlen($fields[ Fields::FIELD_TEXT_BOX ])): ?>
                        <h2 class="<?php echo $fields[ Fields::FIELD_CMS_COLOR_PALETTE_TEXT ]; ?> <?php if ($fields[ Fields::FIELD_BOLD_SELECTOR ])
                        {
                            echo 'is-bold';
                        } ?>  "><?php echo $fields[ Fields::FIELD_TEXT_BOX ]; ?></h2>
                    <?php endif; ?>
                    <?php echo $fields[ Fields::FIELD_TEXT_AREA ]; ?>
                </div>
                
                <?php if (!$column_size) : ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>