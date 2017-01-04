<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<div class="container">
    <div class="introduction-text">
        <div class="row">
            <div class="small-12 columns">
                <h1><?= Html::encode($this->title) ?></h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="small-12 medium-10 medium-offset-1 columns">
            <div class="alert-box warning radius">
                <?= nl2br(Html::encode($message)) ?>
            </div>
            <p>
                <?= Yii::t('app', 'The above error occurred while the Web server was processing your request.') ?>
            </p>
            <p>
                <?= Yii::t('app', 'Please contact us if you think this is a server error. Thank you.') ?>
            </p>
            <p>
                <?php if (\Yii::$app->user->isGuest): ?>
                    <?= Html::a(\Yii::t('app', 'Go Home'), "/") ?>
                <?php else: ?>
                    <?= Html::a(\Yii::t('app', 'Logout'), "/logout") ?>
                <?php endif; ?>
            </p>
        </div>
    </div>
</div>