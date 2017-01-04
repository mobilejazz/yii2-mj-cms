<?php
use mobilejazz\yii2\cms\common\models\Components;
use mobilejazz\yii2\cms\common\models\ContentComponent;

/**
 * @var yii\web\View       $this
 * @var int                $size
 * @var ContentComponent   $component
 * @var ContentComponent[] $children
 */

$fields = Components::getFieldsFromComponentAsArray($component);
?>

<div class="row">
    <div class="small-12 medium-10 medium-offset-1 columns">
        <div class="expandable row">
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
            } ?>
        </div>
    </div>
</div>