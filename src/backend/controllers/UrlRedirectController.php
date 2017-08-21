<?php

namespace mobilejazz\yii2\cms\backend\controllers;

use mobilejazz\yii2\cms\backend\models\search\UrlRedirectSearch;
use mobilejazz\yii2\cms\common\AuthHelper;
use mobilejazz\yii2\cms\common\models\UrlRedirect;
use mobilejazz\yii2\cms\common\models\User;
use dmstr\bootstrap\Tabs;
use yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;

/**
 * UrlRedirectController implements the CRUD actions for UrlRedirect model.
 */
class UrlRedirectController extends Controller
{

    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     */
    public $enableCsrfValidation = false;


    /**
     * Checks if a user is Allowed to see this content.
     *
     * @param User $user
     *
     * @return boolean
     */
    public static function isAllowed(User $user)
    {
        return $user->role === User::ROLE_ADMIN;
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
                        'actions'       => [
                            'bulk',
                            'create',
                            'delete',
                            'update',
                        ],
                        'allow'         => false,
                        'matchCallback' => function ($rule, $action)
                        {
                            $writeLockConfig = ArrayHelper::getValue(\Yii::$app->params, 'writeLock', []);
                            return array_key_exists(\Yii::$app->params[ 'environment' ], $writeLockConfig);
                        },
                        'denyCallback'  => AuthHelper::denyCallback(function ()
                        {
                            Yii::$app->session->setFlash('error',
                                Yii::t('backend', 'Sorry, you can not access this page in a PRODUCTION ENVIRONMENT.'));

                        }),
                    ],
                    [
                        'allow' => true,
                        'roles' => [ 'admin' ],
                    ],
                    [
                        'allow'        => false,
                        'denyCallback' => AuthHelper::denyCallback(function ()
                        {
                            Yii::$app->session->setFlash('error',
                                Yii::t('backend', 'Sorry, only Administrators and Translators can edit/create/update users.'));
                            
                        }),
                    ],
                ],
            ],
        ];
    }


    public function actionBulk()
    {
        if (Yii::$app->request->isPost)
        {
            $action    = Yii::$app->request->post('action');
            $selection = (array) Yii::$app->request->post('selection');

            if (isset($action) && strlen($action) > 0)
            {
                foreach ($selection as $item)
                {
                    /** @var UrlRedirect $e */
                    $e = UrlRedirect::findOne($item);
                    switch ($action)
                    {
                        case 'delete':
                            $e->delete();
                            break;
                    }
                }
            }
        }

        return $this->redirect(Url::previous());
    }


    /**
     * Creates a new UrlRedirect model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UrlRedirect;

        try
        {
            if ($model->load($_POST) && $model->save())
            {
                return $this->redirect(Url::previous());
            }
            elseif (!\Yii::$app->request->isPost)
            {
                $model->load($_GET);
            }
        }
        catch (\Exception $e)
        {
            $msg = (isset($e->errorInfo[ 2 ])) ? $e->errorInfo[ 2 ] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        if (\Yii::$app->request->isAjax)
        {
            return $this->renderAjax('create', [ 'model' => $model ]);
        }

        return $this->render('create', [ 'model' => $model ]);
    }


    /**
     * Deletes an existing UrlRedirect model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        try
        {
            $this->findModel($id)
                 ->delete();
        }
        catch (\Exception $e)
        {
            $msg = (isset($e->errorInfo[ 2 ])) ? $e->errorInfo[ 2 ] : $e->getMessage();
            \Yii::$app->getSession()
                      ->setFlash('error', $msg);

            return $this->redirect(Url::previous());
        }

        // TODO: improve detection
        $isPivot = strstr('$id', ',');
        if ($isPivot == true)
        {
            return $this->redirect(Url::previous());
        }
        elseif (isset(\Yii::$app->session[ '__crudReturnUrl' ]) && \Yii::$app->session[ '__crudReturnUrl' ] != '/')
        {
            Url::remember(null);
            $url                                     = \Yii::$app->session[ '__crudReturnUrl' ];
            \Yii::$app->session[ '__crudReturnUrl' ] = null;

            return $this->redirect($url);
        }
        else
        {
            return $this->redirect([ 'index' ]);
        }
    }


    /**
     * Finds the UrlRedirect model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return UrlRedirect the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UrlRedirect::findOne($id)) !== null)
        {
            return $model;
        }
        else
        {
            throw new HttpException(404, Yii::t('backend', 'The requested page does not exist.'));
        }
    }


    /**
     * Lists all UrlRedirect models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new UrlRedirectSearch;
        $dataProvider = $searchModel->search($_GET);

        Tabs::clearLocalStorage();

        Url::remember();
        \Yii::$app->session[ '__crudReturnUrl' ] = null;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }


    /**
     * Updates an existing UrlRedirect model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load($_POST) && $model->save())
        {
            return $this->redirect(Url::previous());
        }
        else
        {
            if (\Yii::$app->request->isAjax)
            {
                return $this->renderAjax('update', [
                    'model' => $model,
                ]);
            }

            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Displays a single UrlRedirect model.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        \Yii::$app->session[ '__crudReturnUrl' ] = Url::previous();
        Url::remember();
        Tabs::rememberActiveState();

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
}
