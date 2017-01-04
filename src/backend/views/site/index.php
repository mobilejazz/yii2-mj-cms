<?php

use mobilejazz\yii2\cms\backend\modules\i18n\controllers\I18nMessageController;
use mobilejazz\yii2\cms\backend\modules\i18n\models\I18nMessage;
use mobilejazz\yii2\cms\common\models\ContentSource;
use mobilejazz\yii2\cms\common\models\Views;
use mobilejazz\yii2\cms\common\widgets\BoxPanel;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View
 * @var $contents ContentSource[]
 * @var $recent   ContentSource[]
 */

$this->title = Yii::$app->name;
?>
<div class="site-index">

    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <!-- Overview -->
            <?php BoxPanel::begin([
                'title' => Yii::t('backend', 'Overview'),
            ]) ?>
            <p>Welcome <?= Html::a(Yii::$app->user->identity->name, [ '/user/update', 'id' => Yii::$app->user->id ]) ?></p>

            <p><?= Yii::t('backend', 'This is an overview of what the site contains') ?>:</p>
            <ul class="list-unstyled dashboard-icons">
                <?php
                /**
                 * @var integer $index
                 * @var array   $content
                 */
                foreach ($contents as $index => $content): ?>
                    <li><i class="fa <?= Views::getViewIcon($index) ?>"></i>
                        <?= Html::a(count($content) . " " . Views::getViewName($index),
                            [ '/content-source/index?ContentSourceSearch[view]=' . $index ]); ?>
                    </li>
                <?php endforeach ?>
            </ul>
            <?php BoxPanel::end() ?>
            <!-- /overview -->


            <!-- Alerts -->
            <?php BoxPanel::begin([
                'title' => Yii::t('backend', 'Alerts'),
            ]) ?>
            <ul class="list-unstyled dashboard-icons">
                <!-- Hide Translations notification while we're in english. -->
                <?php if (I18nMessageController::isAllowed(\Yii::$app->user->getIdentity())): ?>
                    <li><i class="fa fa-language"></i>
                        <?= Html::a(Yii::t("backend", "You have {count} missing translations",
                            [ 'count' => I18nMessage::countMissingTranslations() ]), [ '/i18n/i18n-message/index' ]) ?></li>
                <?php endif; ?>
                <li><i class="fa fa-exclamation-circle"></i> Any other alert which should be noticed (Todo)</li>

            </ul>
            <?php BoxPanel::end() ?>
            <!-- /alerts -->
        </div>

        <div class="col-xs-12 col-sm-6">
            <!-- Recent -->
            <?php BoxPanel::begin([
                'title' => Yii::t('backend', 'Recent changes'),
            ]) ?>
            <table class="table table-striped">
                <?php foreach ($recent as $r): ?>
                    <?php if ($r->status == ContentSource::STATUS_DELETED)
                    {
                        continue;
                    } ?>
                    <tr>
                        <td>
                            <?= Html::a($r->getTitle(), [ '/content-source/update', 'id' => $r->id ]) ?>
                        </td>
                        <td class="text-muted"><?= Yii::t("backend", "Updated on") ?> <?= date("m/d/y", $r->updated_at) ?> <?= Yii::t("backend",
                                "by") ?> <?= $r->updater->name ?></td>
                    </tr>
                <?php endforeach ?>
            </table>
            <?php BoxPanel::end() ?>
            <!-- /recent -->
        </div>
    </div>
</div>
