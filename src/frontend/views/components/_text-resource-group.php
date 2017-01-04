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

if ( isset( $children ) ) {

}

$count_children = ( isset( $children ) ? count( $children ) : false );
$reduced_width = array( 1, 2, 3, 6, 9 );
?>
<div class="recommended-products-block">
    <div class="row">
        <?php
            if ( $count_children && in_array( $count_children, $reduced_width ) ) {
                echo '<div class="small-12 medium-10 medium-offset-1 columns">';
            } else {
                echo '<div class="small-12 columns">';
            }
        ?>
            <div class="row" data-equalizer data-equalizer-mq="medium-up">
                <?php
                $cols = Components::calculateInnerComponentColumnSize(count($children));
                foreach ($children as $key => $child)
                {
                    echo $this->render("_" . $child->type, [
                        'size'        => $size,
                        'column_size' => $cols[ $key ],
                        'component'   => $child,
                        'children'    => null,
                    ]);
                } ?>
            </div>
        </div>
    </div>
</div>