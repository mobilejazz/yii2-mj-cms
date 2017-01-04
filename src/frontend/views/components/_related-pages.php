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

<ul>
    <?php foreach ($component->componentFields as $field): ?>
        <li><?= $field->type ?>: <?= strlen($field->text) != 0 ? $field->text : "EMPTY VALUE" ?></li>
    <?php endforeach; ?>
</ul>


<div class="related-pages">
	<div class="related-pages-header row">
		<div class="small-12 columns">
			<p>You may be interested in</p>
		</div>
	</div>
	<div class="related-pages-body row">
		<div class="small-12 medium-6 large-3 columns">
			<img src="../images/placeholder.png">
			<p class="related-page-title">
				Lorem ipsum dolor sit amet, consectetur
			</p>
			<p class="related-page-description">
				Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
			</p>
			<p>
				<a class="button">View it here</a>
			</p>
		</div>
		<div class="small-12 medium-6 large-3 columns">
			<img src="../images/placeholder.png">
			<p class="related-page-title">
				Lorem ipsum dolor sit amet, consectetur
			</p>
			<p class="related-page-description">
				Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
			</p>
			<p>
				<a class="button">View it here</a>
			</p>
		</div>
		<div class="small-12 medium-6 large-3 columns">
			<img src="../images/placeholder.png">
			<p class="related-page-title">
				Lorem ipsum dolor sit amet, consectetur
			</p>
			<p class="related-page-description">
				Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
			</p>
			<p>
				<a class="button">View it here</a>
			</p>
		</div>
		<div class="small-12 medium-6 large-3 columns">
			<img src="../images/placeholder.png">
			<p class="related-page-title">
				Lorem ipsum dolor sit amet, consectetur
			</p>
			<p class="related-page-description">
				Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
			</p>
			<p>
				<a class="button">View it here</a>
			</p>
		</div>
	</div>
</div>