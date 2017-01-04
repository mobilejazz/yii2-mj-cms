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

<div class="text-with-heading">
    <div class="row">
        <div class="small-12 medium-10 medium-offset-1 columns">
            <?php if (strlen($fields[ Fields::FIELD_TEXT_BOX ])) : ?>
                <h2><?php echo $fields[ Fields::FIELD_TEXT_BOX ]; ?></h2>
            <?php endif; ?>

            <div class="row">
                <div class="<?php echo $fields[ Fields::FIELD_LTR_SWITCH_INPUT ] ? '' : 'medium-push-7'; ?> small-12 medium-5 columns">
                    <div class="image-wrapper">
                        <img src="<?php echo $fields[ Fields::FIELD_IMAGE ]; ?>">
                    </div>
                </div>

                <div class="<?php echo $fields[ Fields::FIELD_LTR_SWITCH_INPUT ] ? '' : 'medium-pull-5'; ?> small-12 medium-7 columns">
                    <div class="text-content">
                        <?php echo $fields[ Fields::FIELD_TEXT_AREA ]; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>