<?php

/**
 * @var $this  yii\web\View
 * @var $model WebFormDetail
 */

use mobilejazz\yii2\cms\common\models\WebFormDetail;

$this->title                     = $model->title;
$this->params[ 'breadcrumbs' ][] = $this->title;
?>

    <div class="highlighted-image">
        <div class="row block-min-height block-frame" data-equalizer data-equalizer-mq="medium-up">
            <!-- Text -->
            <div class="small-12 medium-8 columns background-navy-blue" data-equalizer-watch>
                <div class="block-padding">
                    <h2 class="block-title">
                        <?= $model->title ?>
                    </h2>
                </div>
            </div>

            <!-- Image -->
            <div class="small-12 medium-4 columns background-cobalt-blue block-image" data-equalizer-watch
                 style="background-image: url('<?= \Yii::t('url', 'form-submission-image-url'); ?>');">
                <img src="<?= \Yii::t('url', 'form-submission-image-url'); ?>" alt="">
            </div>

            <!-- Frame -->
            <div class="frame frame-light-blue"></div>
        </div>
    </div>

    <!-- Text with Heading -->
    <div class="text-with-heading">
        <div class="row">
            <div class="small-12 medium-10 medium-offset-1 columns">
                <h2><?= \Yii::t('app', 'Thank You'); ?></h2>
                <?= $model->message ?>
            </div>
        </div>
    </div>

<?php
$this->registerJs($model->script);
?>