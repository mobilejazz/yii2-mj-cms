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

<div class="references">
	<div class="row">
		<div class="small-12 medium-10 medium-offset-1 columns">
			<div class="references-block">
				<div class="references-header">
					<?php echo $component->title; ?>
				</div>

				<div class="references-body">
				<!-- FIXME: The only field for reference is a text-area. I believe it should be a repeatable item with : reference_id, reference_anchor, reference_text
				-->
					<?php echo $fields[Fields::FIELD_TEXT_AREA]; ?>
				</div>
			</div>
		</div>
	</div>
</div>