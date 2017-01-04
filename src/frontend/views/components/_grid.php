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
<div class="grid">
    <?php
    $cols = Components::calculateInnerComponentColumnSize(count($children));

    // Calculate classes based on number of columns and widths
    $col_count = count($children);
    $col_sum   = array_sum($cols);

    if ($col_count == 6)
    {
        $column_class = "grid-col-sm-12 grid-col-md-6 grid-col-lg-4";
    }
    else if ($col_count % 4 == 0)
    {
        $column_class = "grid-col-sm-12 grid-col-md-6 grid-col-lg-3";
    }
    else if ($col_count % 2 == 0)
    {
        $column_class = "grid-col-sm-12 grid-col-md-6";
    }
    else if ($col_sum % 6 == 0)
    {
        $column_class = "grid-col-sm-12 grid-col-md-4";
    }
    else
    {
        $column_class = "grid-col-sm-12 grid-col-md-6";
    }

    foreach ($children as $key => $child)
    {
        echo $this->render("_" . $child->type, [
            'size'        => $size,
            //'column_size' => $cols[ $key ],
            'column_size' => $column_class,
            'component'   => $child,
            'children'    => null,
        ]);
    }
    ?>
</div>