<?php
/* @var $this \yii\web\View */
/* @var $content string */

use mobilejazz\yii2\cms\common\models\Menu;
use mobilejazz\yii2\cms\frontend\assets\FrontendAsset;
use mobilejazz\yii2\cms\frontend\views\utils\NavUtils;
use mobilejazz\yii2\cms\frontend\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;


FrontendAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

        $menu = Menu::findOne([ 'key' => 'main-menu', ]);
        $navItems = NavUtils::buildMenu($menu);
        
        if (Yii::$app->user->isGuest) {
            $navItems[] = ['label' => Yii::t('app', 'Signup'), 'url' => Yii::t('url', 'signup')];
            $navItems[] = ['label' => Yii::t('app', 'Login'), 'url' => Yii::t('url', 'login')];
        } else {
            $navItems[] = ['label' => Yii::t('app', 'Logout'), 'url' => Yii::t('url', 'logout')];
        }

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $navItems,
        ]);

    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>