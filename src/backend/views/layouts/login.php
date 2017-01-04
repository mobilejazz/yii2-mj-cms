<?php

use mobilejazz\yii2\cms\backend\assets\BackendAsset;
use yii\bootstrap\Alert;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

$bundle = BackendAsset::register($this);

$this->params[ 'body-class' ] = array_key_exists('body-class', $this->params) ? $this->params[ 'body-class' ] : null;
?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?php echo Yii::$app->language ?>">
    <head>
        <meta charset="<?php echo Yii::$app->charset ?>">
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

        <?php echo Html::csrfMetaTags() ?>
        <title><?php echo Html::encode($this->title) ?></title>
        <?php $this->head() ?>

    </head>
    <?php echo Html::beginTag('body', [
        'class' => implode(' ', [
            ArrayHelper::getValue($this->params, 'body-class'),
            'login-page',
        ]),
    ]) ?>
    <?php $this->beginBody() ?>
    <?php if (Yii::$app->session->hasFlash('info')): ?>
        <?php echo Alert::widget([
            'body'    => ArrayHelper::getValue(Yii::$app->session->getFlash('info'), 'body'),
            'options' => ArrayHelper::getValue(Yii::$app->session->getFlash('info'), 'options'),
        ]) ?>
    <?php endif; ?>
    <?php echo $content ?>
    <?php $this->endBody() ?>
    <?php echo Html::endTag('body') ?>
    </html>
<?php $this->endPage() ?>