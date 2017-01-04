<?php

use mobilejazz\yii2\cms\common\models\ContentComponent;

/**
 * @var yii\web\View     $this
 * @var int              $size
 * @var ContentComponent $component
 */
?>
<ul>
    <?php foreach ($component->componentFields as $field): ?>
        <li><?= $field->type ?>: <?= strlen($field->text) != 0 ? $field->text : "EMPTY VALUE" ?></li>
    <?php endforeach; ?>
</ul>
