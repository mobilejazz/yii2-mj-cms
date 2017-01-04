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
                <div
                    class="image-wrapper <?php echo $fields[ Fields::FIELD_LTR_SWITCH_INPUT ] ? 'align-left' : 'align-right'; ?> small-12 medium-5 columns">
                    <a href="<?php echo $fields[ Fields::FIELD_LINK_URL ]; ?>"
                        <?php if (!$fields[ Fields::FIELD_LINK_TARGET ])
                        {
                            echo 'target="_blank"';
                        } ?>>
                        <img src="<?php echo $fields[ Fields::FIELD_IMAGE ]; ?>">
                    </a>
                </div>

                <div class="small-12 medium-7 columns">
                    <div class="text-content">
                        <?php echo $fields[ Fields::FIELD_TEXT_AREA ]; ?>

                        <?php if (strlen($fields[ Fields::FIELD_LINK_URL ])): ?>
                            <p>
                                <a href="<?php echo $fields[ Fields::FIELD_LINK_URL ]; ?>"
                                   class="button <?php echo $fields[ Fields::FIELD_LINK_COLOR ]; ?>" <?php if (!$fields[ Fields::FIELD_LINK_TARGET ])
                                {
                                    echo 'target="_blank"';
                                } ?>>
                                    <?php echo $fields[ Fields::FIELD_LINK_NAME ]; ?>
                                </a>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>