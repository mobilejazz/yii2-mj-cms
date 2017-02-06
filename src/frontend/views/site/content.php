<?php
use mobilejazz\yii2\cms\common\models\ContentComponent;
use mobilejazz\yii2\cms\common\models\User;
use yii\helpers\Html;

/**
 * @var $this  yii\web\View
 * @var $slug  \mobilejazz\yii2\cms\common\models\ContentSlug
 * @var $lang  string
 * @var $model \mobilejazz\yii2\cms\common\models\ContentSource
 */

// Register Meta Tags
$tags        = $model->getCurrentMetaTags();
$this->title = $slug->title . ' | ' . \Yii::$app->name;

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

// GET CURRENT SLUG TO GENERATE SOME FALSE BREADCRUMBS BASED ON THE SLUG USED.
$slug       = $model->getCurrentSlug(\Yii::$app->language);
$categories = explode("/", $slug->slug);
if (isset($categories) && count($categories) > 1)
{
    unset($categories[ count($categories) - 1 ]);
    foreach ($categories as $category)
    {
        $category                        = str_replace("-", " ", $category);
        $category                        = ucwords($category);
        $this->params[ 'breadcrumbs' ][] = [
            'label' => $category,
            'class' => '',
        ];
    }
}
$this->params[ 'breadcrumbs' ][] = [
    'label'    => $slug->title,
    'url'      => "#",
    'template' => "<li class=\"current\">{link}</li>\n",
    'class'    => 'current',
];

if (!\Yii::$app->user->isGuest)
{
    /** @var User $user */
    $user = \Yii::$app->user->getIdentity();
    if ($user->role == User::ROLE_ADMIN || $user->role == User::ROLE_EDITOR)
    {
        echo Html::a(\Yii::t('app', 'Edit') . ' <i class="fa fa-pencil" aria-hidden="true"></i>',
            '/admin/content-source/update?id=' . $model->id . '', [
                'class' => 'button round',
                'style' => [
                    'position' => 'fixed',
                    'top'      => '20px',
                    'right'    => '20px',
                    'z-index'  => '1000'
                ],
            ]);
    }
}
?>
<div class="site-content">
    <?php
    /** @var array $component_map */
    $component_map = $model->getOrderedContentComponentsByGroup($lang);
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
            'size'      => count($group),
            'component' => $group[ 0 ],
            'children'  => $children,
        ]));
    }
    ?>
</div>
