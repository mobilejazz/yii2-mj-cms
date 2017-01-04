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

<?php if(strlen($fields[Fields::FIELD_LINK_URL])):?>
	<div class="link">
		<div class="row">
		    <div class="small-12 medium-10 medium-offset-1 columns">
				<p>
					<a href="<?php echo $fields[Fields::FIELD_LINK_URL]; ?>" <?php if(!$fields[Fields::FIELD_LINK_TARGET]){ echo 'target="_blank"'; } ?>>
						<?php echo $fields[Fields::FIELD_LINK_NAME]; ?>
					</a>
				</p>
			</div>
		</div>
	</div>
<?php endif; ?>