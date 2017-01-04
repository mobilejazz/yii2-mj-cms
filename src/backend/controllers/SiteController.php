<?php

namespace mobilejazz\yii2\cms\backend\controllers;

use mobilejazz\yii2\cms\backend\models\PasswordResetRequestForm;
use mobilejazz\yii2\cms\backend\models\ResetPasswordForm;
use mobilejazz\yii2\cms\common\models\ContentSource;
use mobilejazz\yii2\cms\common\models\Locale;
use mobilejazz\yii2\cms\common\models\LoginForm;
use mobilejazz\yii2\cms\common\models\User;
use Exception;
use yii;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{

    public function actionIndex()
    {
        // Get the content types and numbers.
        /** @var ContentSource[] $contents */
        $contents = ContentSource::getAsContentMap();
        $recent   = ContentSource::getRecentlyChanged();

        return $this->render('index', [
            'contents' => $contents,
            'recent'   => $recent,
        ]);
    }


    /**
     * @param $token
     *
     * @return $this|string
     * @throws BadRequestHttpException
     * @throws Exception
     */
    public function actionResetPassword($token)
    {
        try
        {
            $model        = new ResetPasswordForm($token);
            $this->layout = 'login';
        }
        catch (InvalidParamException $e)
        {
            throw new BadRequestHttpException($e->getMessage());
        }

        try
        {
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword())
            {

                return $this->render('passwordResetCompleted');
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


    public function actionRequestPasswordReset()
    {
        $model        = new PasswordResetRequestForm();
        $this->layout = 'login';
        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $model->sendEmail();
            Yii::$app->getSession()
                     ->setFlash('info', \Yii::t('app',
                         'Your request for a password reset has been received. You should shortly receive an email with details of what to do next to change your password.'));

            return $this->render('requestPasswordResetTokenSent');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
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
            return $this->goBack();
        }
        else
        {
            $this->layout = 'login';

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


    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error'      => [
                'class' => 'yii\web\ErrorAction',
            ],
            'set-locale' => [
                'class'            => 'mobilejazz\yii2\cms\common\actions\SetLocaleAction',
                'locales'          => Locale::getAllKeys(),
                'localeCookieName' => '_backendLocale'
            ],
        ];
    }


    public function beforeAction($action)
    {
        $this->layout = Yii::$app->user->isGuest || Yii::$app->user->getIdentity()->role == User::ROLE_USER ? 'login' : 'common';

        return parent::beforeAction($action);
    }


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [ 'login', 'error', 'set-locale', 'logout', 'request-password-reset', 'reset-password' ],
                        'allow'   => true,
                    ],
                    [
                        'actions' => [ 'index' ],
                        'allow'   => true,
                        'roles'   => [ 'admin', 'editor' ],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'logout' => [ 'post' ],
                ],
            ],
        ];
    }
}
