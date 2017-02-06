<?php

use mobilejazz\yii2\cms\common\models\ContentComponent;

/**
 * @var $this  yii\web\View
 * @var $model \mobilejazz\yii2\cms\common\models\ContentSource
 */
$this->title = $model->getTitle() . ' | ' . \Yii::$app->name;
// Register Title Meta TAG alone first.
$this->registerMetaTag([
    'name'    => 'title',
    "content" => $this->title
]);
// Register Meta Tags
$tags = $model->getCurrentMetaTags();
foreach ($tags as $tag)
{
    $this->registerMetaTag([
        'name'    => $tag->name,
        'content' => $tag->content
    ]);
}
// Register Relationships.
$rels = $model->getCurrentRels();
foreach ($rels as $rel)
{
    $this->registerMetaTag([
        'rel'      => $rel->rel,
        'hreflang' => $rel->hreflang,
        'href'     => $rel->href,
    ]);
}
?>
<div class="site-content">
    <?php
    /** @var array $component_map */
    $component_map = $model->getOrderedContentComponentsByGroup(\Yii::$app->language);
    /** @var ContentComponent[] $group */
    foreach ($component_map as $group)
    {
        $type = $group[ 0 ]->type;

        $f = "_" . $type;

        $children = null;
        if (isset($group[ 'children' ]))
        {
            /** @var ContentComponent[] $children */
            $children = $group[ 'children' ];
        }

        echo($this->render('../components/' . $f, [
            'size'        => count($group),
            'column_size' => null,
            'component'   => $group[ 0 ],
            'children'    => $children,
        ]));
    }
    ?>
</div>
