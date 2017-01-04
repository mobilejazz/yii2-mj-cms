<?php

namespace mobilejazz\yii2\cms\backend\modules\i18n\controllers;

use mobilejazz\yii2\cms\backend\modules\i18n\models\I18nMessage;
use mobilejazz\yii2\cms\backend\modules\i18n\models\I18nSourceMessage;
use mobilejazz\yii2\cms\backend\modules\i18n\models\search\I18nMessageSearch;
use mobilejazz\yii2\cms\common\AuthHelper;
use mobilejazz\yii2\cms\common\models\User;
use yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * I18nMessageController implements the CRUD actions for I18nMessage model.
 */
class I18nMessageController extends Controller
{

    /**
     * Checks if a user is Allowed to see this content.
     *
     * @param User $user
     *
     * @return boolean
     */
    public static function isAllowed(User $user)
    {
        if (\Yii::$app->language === 'en')
        {
            return false;
        }

        return $user->role === User::ROLE_ADMIN || $user->role === User::ROLE_TRANSLATOR;
    }


    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [ 'admin', 'translator' ],
                    ],
                    [
                        'allow'        => false,
                        'denyCallback' => AuthHelper::denyCallback(function ()
                        {
                            Yii::$app->session->setFlash('error',
                                Yii::t('backend', 'Sorry, only Administrators and Translators can edit/create/update translations.'));
                            
                        }, Yii::$app->homeUrl),
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => [ 'post' ],
                ],
            ],
        ];
    }


    /**
     * Creates a new I18nMessage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new I18nMessage();

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect([ 'index' ]);
        }
        else
        {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Deletes an existing I18nMessage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @param string  $language
     *
     * @return mixed
     */
    public function actionDelete($id, $language)
    {
        $this->findModel($id, $language)
             ->delete();

        return $this->redirect([ 'index' ]);
    }


    /**
     * Finds the I18nMessage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @param string  $language
     *
     * @return I18nMessage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $language)
    {
        if (($model = I18nMessage::findOne([ 'id' => $id, 'language' => $language ])) !== null)
        {
            return $model;
        }
        else
        {
            throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
        }
    }


    /**
     * Lists all I18nMessage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new I18nMessageSearch();

        $params       = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($params);
        Url::remember(Yii::$app->request->getUrl(), 'i18n-messages-filter');
        $categories = ArrayHelper::map(I18nSourceMessage::find()
                                                        ->select('category')
                                                        ->distinct()
                                                        ->all(), 'category', 'category');

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'categories'   => $categories,
        ]);
    }


    /**
     * List sll the Missing translations for the current language.
     */
    public function actionMissingTranslations()
    {
        $searchModel               = new I18nMessageSearch();
        $searchModel->missing_only = true;
        $params                    = Yii::$app->request->queryParams;
        $dataProvider              = $searchModel->search($params);
        $categories                = ArrayHelper::map(I18nSourceMessage::find()
                                                                       ->select('category')
                                                                       ->distinct()
                                                                       ->all(), 'category', 'category');

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'categories'   => $categories,
        ]);
    }


    /**
     * Scans the code for new Strings to include in the database and update old ones that are no longer used.
     * @return \yii\web\Response
     * @throws \yii\console\Exception
     */
    public function actionScanForNewMessages()
    {
        //default console commands outputs to STDOUT so this needs to be declared for wep app
        if (!defined('STDOUT'))
        {
            define('STDOUT', fopen('/tmp/stdout', 'w'));
        }

        // Run script to extract and clean up all the yii:t calls
        //extract messages command
        $migration = new MessageController('message', Yii::$app);
        $migration->runAction('extract', [ '@common/config/extract.php' ]);

        //extract messages command end

        return $this->redirect([ 'index' ]);
    }


    /**
     * Updates an existing I18nMessage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @param string  $language
     *
     * @return mixed
     */
    public function actionUpdate($id, $language)
    {
        $model = $this->findModel($id, $language);

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect(Url::previous('i18n-messages-filter') ?: [ 'index' ]);
        }
        // DISPLAY THE MODAL.
        elseif (Yii::$app->request->isAjax)
        {
            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }
        else
        {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
}
