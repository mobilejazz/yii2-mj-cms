<?php

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>"/>
        <style type="text/css">
          
        </style>
        <?php $this->head() ?>
    </head>
    <body class="background-light-blue"
          style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;font-family:'Effra', sans-serif;font-size:15px;line-height:1.14;color:rgba(0, 0, 0, 0.87);background-color:#B9D9EB;">
    <?php $this->beginBody() ?>
    <?= $content ?>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>