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

<div class="image-with-caption">
	<div class="row">
		<div class="small-12 large-10 large-offset-1 columns">
			<img src="<?php echo $fields[Fields::FIELD_IMAGE]; ?>">
			<p><?php echo $fields[Fields::FIELD_TEXT_BOX]; ?></p>
		</div>
	</div>
</div>