<?php
/**
 * @var $this yii\web\View
 */
use mobilejazz\yii2\cms\backend\assets\BackendAsset;
use mobilejazz\yii2\cms\backend\widgets\Menu;
use mobilejazz\yii2\cms\common\models\Locale;
use yii\bootstrap\Alert;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

$bundle = BackendAsset::register($this);
$langs  = Locale::getAllKeys();
?>

<?php $this->beginContent('@mobilejazz/yii2/cms/backend/views/layouts/base.php'); ?>
    <div class="wrapper">
        <!-- header logo: style can be found in header.less -->
        <header class="main-header">
            <a href="/admin/" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                <?= Yii::$app->name ?>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only"><?= Yii::t("app", "Toggle navigation") ?></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- Allow extensions of the top menu here for implementation projects -->
                        <?= $this->render('../layout-extensions/top-menu'); ?>
                        <li><p class="nav navbar-text" style="color: #FFFFFF;"><?= \Yii::t('backend', 'Current language:'); ?> </p></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <span class="flag-icon flag-icon-<?= Locale::getCurrentCountryCode() ?>" style="margin-right: 4px;"></span>
                                <?= Locale::getAllLocalesAsMap()[ Yii::$app->language ] ?>
                                <i class="caret"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <?php
                                foreach ($langs as $lang)
                                {
                                    /** @var Locale $current */
                                    $current = Locale::findByIdentifier($lang);
                                    if ($current->isUsed())
                                    {
                                        ?>
                                        <li>
                                            <a href="/admin/site/set-locale?locale=<?= $lang ?>">
                                                <span class="flag-icon flag-icon-<?= $current->country_code ?>"></span>
                                                Edit in <?= $current->label ?>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                        </li>
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="<?= $this->assetManager->getAssetUrl($bundle, 'img/logo.png') ?>"
                                     class="user-image">
                                <span><?= Yii::$app->user->identity->name ?> <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header light-blue">
                                    <img
                                        src="<?= $this->assetManager->getAssetUrl($bundle, 'img/logo.png') ?>"
                                        class="img-circle" alt="User Image"/>

                                    <p>
                                        <?= Yii::$app->user->identity->name ?>
                                        <small>
                                            <?= Yii::t('backend', 'Member since') . ' ' . date("M d, Y", Yii::$app->user->identity->created_at); ?>
                                        </small>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <?= Html::a(Yii::t('backend', 'Profile'), [ '/user/update', "id" => Yii::$app->user->identity->getId() ],
                                            [ 'class' => 'btn btn-primary btn-flat' ]) ?>
                                    </div>
                                    <div class="pull-right">
                                        <?= Html::a(Yii::t('backend', 'Logout'), [ '/site/logout' ],
                                            [ 'class' => 'btn btn-primary btn-flat', 'data-method' => 'post' ]) ?>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <?php
                /** @var mobilejazz\yii2\cms\common\models\User $user */
                $user = Yii::$app->user->getIdentity();

                $menuItems = Yii::$app->getModule('cmsbackend')->sidebar->build();

                /** @noinspection PhpIncludeInspection */

                ?>
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <?= Menu::widget([
                    'options'         => [ 'class' => 'sidebar-menu' ],
                    'linkTemplate'    => '<a href="{url}">{icon}<span>{label}</span>{right-icon}{badge}</a>',
                    'submenuTemplate' => "\n<ul class=\"treeview-menu\">\n{items}\n</ul>\n",
                    'activateParents' => true,
                    'items'           => $menuItems,
                ]) ?>
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- Right side column. Contains the navbar and content of the page -->
        <aside class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    <?= $this->title ?>
                    <?php if (isset($this->params[ 'subtitle' ])): ?>
                        <small><?= $this->params[ 'subtitle' ] ?></small>
                    <?php endif; ?>
                </h1>

                <?= Breadcrumbs::widget([
                    'tag'   => 'ol',
                    'links' => isset($this->params[ 'breadcrumbs' ]) ? $this->params[ 'breadcrumbs' ] : [ ],
                ]) ?>

                <?php if (Yii::$app->session->hasFlash('error'))
                {
                    ?>
                    <div class="alert alert-danger alert-dismissible" style="    margin-top: 20px;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><?= Yii::t('backend', 'Error') ?></h4>
                        <p><?= Yii::$app->session->getFlash('error') ?></p>
                    </div>
                    <?php
                } ?>

            </section>

            <!-- Main content -->
            <section class="content">
                <?php if (Yii::$app->session->hasFlash('alert')): ?>
                    <?= Alert::widget([
                        'body'    => ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'body'),
                        'options' => ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'options'),
                    ]) ?>
                <?php endif; ?>
                <?= $content ?>
            </section>
            <!-- /.content -->
        </aside>
        <!-- /.right-side -->
    </div><!-- ./wrapper -->


<?php

$this->endContent(); ?>