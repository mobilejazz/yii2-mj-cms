<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title                   = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>This is the About page. You may modify the following file to customize its content:</p>

    <p>
        Current language is: <?= Yii::$app->language ?>
        <br/>
        <?= \Yii::t('app', "This is a new test translation") ?>
        <?= \Yii::t('app', "This is a parameter for a String with a Hellos {world}", [ 'world' => 'WORLDSSSS' ]) ?>
        <?= \Yii::t('app', "This string should be inserted now.") ?>
        <?= \Yii::t('app', "Please, insert a new string into the database") ?>
    </p>

    <code><?= __FILE__ ?></code>
</div>
