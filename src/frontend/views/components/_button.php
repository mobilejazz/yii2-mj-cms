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
$class  = 'button';

// Colour
if ( isset( $fields[ Fields::FIELD_LINK_COLOR ] ) ) {
    $class .= ' ' . $fields[ Fields::FIELD_LINK_COLOR ];
} else if ( isset( $fields[ Fields::FIELD_CMS_COLOR_PALETTE_BACKGROUND ] ) && strpos( $fields[ Fields::FIELD_CMS_COLOR_PALETTE_BACKGROUND ], 'background-') !== false) {
    $color = str_replace( 'background-', 'button-', $fields[ Fields::FIELD_CMS_COLOR_PALETTE_BACKGROUND ] );
    $class .= ' ' . $color;
}

// Old alignment
if (isset($fields[ Fields::FIELD_LTR_SWITCH_INPUT ]))
{
    $class .= ' ' . $fields[ Fields::FIELD_LTR_SWITCH_INPUT ] ? 'align-left' : 'align-right';
}

// New alignment
$alignment = isset( $fields[ Fields::FIELD_BUTTON_ALIGNMENT ] ) ? $fields[ Fields::FIELD_BUTTON_ALIGNMENT ] : '';

// Button
if (strlen($fields[ Fields::FIELD_LINK_URL ])): ?>
    <div class="button-component">
        <div class="row">
            <div class="small-12 medium-10 medium-offset-1 columns <?php echo $alignment; ?>">
                <a href="<?= $fields[ Fields::FIELD_LINK_URL ]; ?>" class="<?= $class ?>" <?= !$fields[ Fields::FIELD_LINK_TARGET ] ? 'target="_blank"' : ''; ?>>
                    <?= $fields[ Fields::FIELD_LINK_NAME ]; ?>
                </a>
            </div>
        </div>
    </div>
<?php endif; ?>
