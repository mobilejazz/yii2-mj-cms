<?php

use yii\helpers\Html;

/**
 * @var $this      yii\web\View
 * @var $name      string
 * @var $message   string
 * @var $exception Exception
 */

$this->title = $name;
?>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="col-md-site-error">
                <h1><?= Html::encode($this->title) ?></h1>
                <div class="alert alert-danger">
                    <?= nl2br(Html::encode($message)) ?>
                </div>
                <p>
                    <?= Yii::t('backend', 'The above error occurred while the Web server was processing your request.') ?>
                </p>
                <p>
                    <?= Yii::t('backend', 'Please contact us if you think this is a server error. Thank you.') ?>
                </p>
                <p>
                    <?php if (\Yii::$app->user->isGuest): ?>
                        <?= Html::a(\Yii::t('backend', 'Go Home'), "/") ?>
                    <?php else: ?>
                        <?= Html::a(\Yii::t('backend', 'Logout'), "/logout") ?>
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>
</div>