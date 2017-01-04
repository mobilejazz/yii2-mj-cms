<?php

use mobilejazz\yii2\cms\common\models\ContentMetaTag;
use mobilejazz\yii2\cms\common\models\ContentSource;
use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var yii\web\View                        $this
 * @var yii\data\ActiveDataProvider         $dataProvider
 * @var \mobilejazz\yii2\cms\frontend\models\ContentSourceSearch $searchModel
 */

$this->title                     = Yii::t('app', 'Search results');
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
    <div class="highlighted-box row">
        <h1 class="highlighted-text">
            <?= \Yii::t('app', 'Search Results'); ?><br><span class="text-light"><?= \Yii::t('app', 'For Your Query'); ?></span>
        </h1>

        <img class="highlighted-image hide-for-small" src="../images/question.png">

        <div class="highlighted-frame"></div>
    </div>
    <div class="introduction-text row">
        <div class="small-12 large-8 columns">
            <p><?= \Yii::t('app',
                    'The following are the results that we have found from your query. If what you are looking for is not in the list, it may be that you are either not allowed to view the content you are looking for or that '); ?></p>
            <p></p>
        </div>
    </div>
<?php echo GridView::widget([
    'dataProvider'     => $dataProvider,
    'options'          => [
        'class' => '',
    ],
    'tableOptions'     => [
        'class' => '',
    ],
    'filterRowOptions' => [
        'class' => '',
    ],
    'layout'           => '{items}',
    'columns'          => [
        [
            'label'  => Yii::t('app', 'Title'),
            'value'  => function ($model)
            {
                /** @var ContentSource $model */
                $slug = $model->getCurrentContentSlug(\Yii::$app->language);
                $url  = Yii::$app->urlManager->createBaseUrl('cmsfrontend/site/content', [
                    'lang' => Yii::$app->language,
                    'slug' => $slug,
                ]);

                return Html::a($model->getTitle(), $url);
            },
            'format' => 'html',
        ],
        [
            'label'  => \Yii::t('app', 'Description'),
            'value'  => function ($model)
            {
                return ContentMetaTag::find()->where([
                    'content_id' => $model->id,
                ])->andWhere([ 'like', 'name', 'description' ])->one()->content;
            },
            'format' => 'text',
        ],
    ],
]);