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

<div class="<?php echo $column_size; ?> square-box">
	<div class="square-content <?php echo $fields[Fields::FIELD_CMS_COLOR_PALETTE_BACKGROUND]; ?>">
		<?php if(strlen($fields[Fields::FIELD_IMAGE])): ?>
		 	<div class="grid-image-wrapper square-content" style="background-image:url(<?php echo $fields[Fields::FIELD_IMAGE]; ?>)"></div>
		<?php else: ?>

			<div class="grid-title">
				<strong><?php echo $fields[Fields::FIELD_TITLE_BOLD]; ?></strong><br/>
				<?php echo $fields[Fields::FIELD_TITLE_NON_BOLD]; ?>
			</div>

			<?php if(strlen($fields[Fields::FIELD_LINK_URL])):?>
				<a href="<?php echo $fields[Fields::FIELD_LINK_URL]; ?>"
				class="button"
				<?php if(!$fields[Fields::FIELD_LINK_TARGET]){ echo 'target="_blank"'; } ?>
				>
					<?php echo $fields[Fields::FIELD_LINK_NAME]; ?>
				</a>

			<?php endif; ?>

		<?php endif; ?>

	</div>
</div>

