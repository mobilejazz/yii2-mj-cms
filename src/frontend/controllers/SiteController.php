<?php
namespace mobilejazz\yii2\cms\frontend\controllers;

use Exception;
use mobilejazz\yii2\cms\common\models\ContentSlug;
use mobilejazz\yii2\cms\common\models\ContentSource;
use mobilejazz\yii2\cms\common\models\Locale;
use mobilejazz\yii2\cms\common\models\LoginForm;
use mobilejazz\yii2\cms\common\models\Menu;
use mobilejazz\yii2\cms\common\models\User;
use mobilejazz\yii2\cms\frontend\models\ContentSourceSearch;
use mobilejazz\yii2\cms\frontend\models\PasswordResetRequestForm;
use mobilejazz\yii2\cms\frontend\models\ResetPasswordForm;
use yii;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Site controller
 */
class SiteController extends Controller
{

    public function actionAbout()
    {
        return $this->render('about');
    }


    public function actionSearch($q = '')
    {
        if ($q == '')
        {
            return $this->goHome();
        }
        $searchModel               = new ContentSourceSearch();
        $searchModel->searchString = $q;
        $dataProvider              = $searchModel->search($_GET);

        return $this->render('search', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionContact()
    {
        /** @var ContentSlug $content_slug */
        $content_slug = ContentSlug::find()
                                   ->where([ 'language' => \Yii::$app->language, 'slug' => \Yii::t('url', 'contact-us') ])
                                   ->one();

        return $this->actionContent(\Yii::$app->language, $content_slug->slug);
    }


    /**
     * Displays the content for the given language and slug.
     *
     * @param $lang
     * @param $slug
     *
     * @return string
     * @throws NotFoundHttpException | ForbiddenHttpException
     */
    public function actionContent($lang, $slug)
    {
        //===============================================//
        // --------- HANDLE REDIRECTS IF NEEDED ---------//
        //===============================================//
        /** @var ContentSlug $content_slug */
        $content_slug = ContentSlug::find()
                                   ->where([ 'language' => $lang, 'slug' => $slug ])
                                   ->one();

        if (!$content_slug->isActive())
        {
            $current_slug = $content_slug->content->getCurrentSlug($content_slug->language);

            $link = '/';

            if(Locale::isMultiLanguageSite()) {
                $link .= $current_slug->language . '/';
            }

            $link .= $current_slug->slug;

            return $this->redirect($link);
        }
        //===============================================//
        // --------- END OF REDIRECTS IF NEEDED ---------//
        //===============================================//

        // Translations
        /** @var ContentSource $model */
        $model = ContentSource::find()
                              ->where([ 'id' => $content_slug->content_id ])
                              ->one();

        // If the model is not set.
        if (!isset($model))
        {
            throw new NotFoundHttpException(Yii::t('app', "It looks like the content you are trying to access can not be found."));
        } // If the model status is a draft, check if the user is an admin.
        if ($model->status === ContentSource::STATUS_DRAFT || $model->status === ContentSource::STATUS_DELETED)
        {
            //Get current user
            /** @var User $user */
            $user = \Yii::$app->user->getIdentity();
            // If this is not an admin and we are in
            // preview throw a ForbiddenHttpException.
            if ($user === null || \Yii::$app->user->isGuest || $user->role === User::ROLE_USER)
            {
                if ($model->status == ContentSource::STATUS_DRAFT)
                {
                    throw new ForbiddenHttpException(Yii::t('app', "You are not allowed to see this page as it is in preview stage."));
                }
                else
                {
                    throw new NotFoundHttpException(Yii::t('app', 'It looks like the content you are trying to access has been deleted.'));
                }
            }
        }

        elseif ($model->status === ContentSource::STATUS_PRIVATE_CONTENT)
        {
            /** @var User $user */
            $user    = \Yii::$app->user->getIdentity();
            $allowed = true;
            if (\Yii::$app->user->isGuest || $user->status == User::STATUS_AWAITING_VALIDATION || $user->status == User::STATUS_INVALIDATED)
            {
                $allowed = false;
            }

            if (!$allowed)
            {
                if (\Yii::$app->user->isGuest)
                {
                    \Yii::$app->session->setFlash('warning', "<div class='important'>" . \Yii::t('app',
                            'Only Validated Users may see this content. Please sign in or register to see it.') . "</div>");
                }
                else if ($user->status == User::STATUS_AWAITING_VALIDATION)
                {
                    \Yii::$app->session->setFlash('warning', "<div class='important'>" . \Yii::t('app',
                            'Your profile is under review. Once validated, you will be able to access the "{content}" content. Thank you', [
                                'content' => $model->getCurrentSlug($lang)->title,
                            ]) . "</div>");
                }

                return $this->redirect(Yii::t('url', '/login'));
            }
        }

        return $this->render('content', [
            'model' => $model,
            'lang'  => $lang,
            'slug'  => $content_slug,
        ]);
    }


    public function actionIndex()
    {
        $model = ContentSource::findOne([ 'is_homepage' => 1 ]);
        if (!isset($model) || is_null($model))
        {
            throw new ServerErrorHttpException(\Yii::t('app',
                'No homepage has been defined in the administration. Please fix this as soon as possible.'));
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }


    public function actionSitemap()
    {
        return $this->render('sitemap', [
            'items' => $menu = Menu::findOne([ 'key' => 'main-menu', ]),
        ]);
    }


    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login())
        {
            // TODO: Go to profile?
            return $this->goHome();
        }
        else
        {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }


    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }


    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $model->sendEmail();
            Yii::$app->getSession()
                     ->setFlash('info', \Yii::t('app',
                         'Your request for a password reset has been received. You should shortly receive an email with details of what to do next to change your password.'));

            return $this->goHome();
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }


    /**
     * @param $token
     *
     * @return SiteController|string|yii\console\Response|yii\web\Response
     * @throws BadRequestHttpException
     * @throws Exception
     */
    public function actionResetPassword($token)
    {
        try
        {
            $model = new ResetPasswordForm($token);
        }
        catch (InvalidParamException $e)
        {
            throw new BadRequestHttpException($e->getMessage());
        }

        try
        {
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword())
            {

                Yii::$app->getSession()
                         ->setFlash('info', \Yii::t('app', 'New password was saved'));

                return Yii::$app->getResponse()
                                ->redirect(Yii::t('url', '/login'));
            }
        }
        catch (Exception $ex)
        {
            throw $ex;
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }


    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error'      => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha'    => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'set-locale' => [
                'class'            => 'mobilejazz\yii2\cms\common\actions\SetLocaleAction',
                'locales'          => Locale::getAllKeys(true),
                'localeCookieName' => '_frontendLocale'
            ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => [ 'logout', 'signup', 'profile', 'register' ],
                'rules' => [
                    [
                        'actions' => [ 'signup', 'register' ],
                        'allow'   => true,
                        'roles'   => [ '?' ],
                    ],
                    [
                        'actions' => [ 'logout', 'profile' ],
                        'allow'   => true,
                        'roles'   => [ '@' ],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'logout' => [ 'get' ]
                ]
            ]
        ];
    }
}
