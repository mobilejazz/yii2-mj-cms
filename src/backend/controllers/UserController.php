<?php

namespace mobilejazz\yii2\cms\backend\controllers;

use mobilejazz\yii2\cms\backend\components\AdminController;
use mobilejazz\yii2\cms\backend\models\search\UserSearch;
use mobilejazz\yii2\cms\common\AuthHelper;
use mobilejazz\yii2\cms\common\models\User;
use yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends AdminController
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
                        'allow' => true,
                        'roles' => [ 'admin' ],
                    ],
                    [
                        'allow'        => false,
                        'denyCallback' => AuthHelper::denyCallback(function ()
                        {
                            Yii::$app->session->setFlash('error', Yii::t('backend', 'Sorry, only Administrators can edit/create/update users.'));
                        }),
                    ],
                ],
            ],
        ];
    }


    /**
     * Performs bulk actions.
     * @return \yii\web\Response
     * @throws \Exception
     */
    public function actionBulk()
    {
        if (Yii::$app->request->isPost)
        {
            $action    = Yii::$app->request->post('action');
            $selection = (array) Yii::$app->request->post('selection');

            if (isset($action) && strlen($action) > 0)
            {
                foreach ($selection as $user)
                {
                    /** @var User $e */
                    $e = User::findOne($user);
                    switch ($action)
                    {
                        case 'deactivate':
                            $e->setStatus(User::STATUS_DELETED);
                            break;
                        case 'activate':
                            $e->setStatus(User::STATUS_ACTIVE);
                            break;
                        case 'delete':
                            $e->delete();
                            break;
                        case 'translator':
                            $e->setRole(User::ROLE_TRANSLATOR);
                            break;
                        case 'editor':
                            $e->setRole(User::ROLE_EDITOR);
                            break;
                        case 'admin':
                            $e->setRole(User::ROLE_ADMIN);
                            break;
                    }
                }
            }
        }

        return $this->redirect('index');
    }


    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->beforeSave($model->isNewRecord) && $model->save())
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
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)
             ->delete();

        return $this->redirect([ 'index' ]);
    }


    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null)
        {
            return $model;
        }
        else
        {
            throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
        }
    }


    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->render('update', [
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


    /**
     * Displays a single User model.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
}
