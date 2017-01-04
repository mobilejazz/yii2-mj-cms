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
			<div class="row">
			    <?php
			        $cols = Components::calculateInnerComponentColumnSize(count($children));
			        foreach ($children as $key => $child)
			        {
			            echo $this->render("_" . $child->type, [
			                'size'      => $size,
			                'column_size'      => $cols[ $key ],
			                'component' => $child,
			                'children'  => null,
			            ]);
			        }
		        ?>
			</div>
		</div>
	</div>
</div>