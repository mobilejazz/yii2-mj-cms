<?php

namespace mobilejazz\yii2\cms\backend\controllers;

use dmstr\bootstrap\Tabs;
use mobilejazz\yii2\cms\backend\models\search\MenuSearch;
use mobilejazz\yii2\cms\common\AuthHelper;
use mobilejazz\yii2\cms\common\models\Locale;
use mobilejazz\yii2\cms\common\models\Menu;
use mobilejazz\yii2\cms\common\models\MenuItem;
use mobilejazz\yii2\cms\common\models\MenuItemTranslation;
use mobilejazz\yii2\cms\common\models\User;
use yii;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends yii\web\Controller
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
                'class' => yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions'       => [
                            'create',
                            'update',
                            'delete',
                            'delete-menu-item',
                            'menu-item',
                            'menu-item-create',
                            'move-down',
                            'bulk',
                            'move-up',
                        ],
                        'allow'         => false,
                        'matchCallback' => function ($rule, $action)
                        {
                            $writeLockEnabled = ArrayHelper::getValue(\Yii::$app->params, 'writeLockEnabled', true);
                            return $writeLockEnabled && \Yii::$app->params[ 'environment' ] === 'prod';
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
                            Yii::$app->session->setFlash('error', Yii::t('backend', 'Sorry, only Administrators can edit/create/update menus.'));
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
                    /** @var Menu $e */
                    $e = Menu::findOne($item);
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
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        /** @var Menu $model */
        $model = new Menu;

        try
        {
            if ($model->load($_POST) && $model->save())
            {
                return $this->redirect([ '/menu/update', 'id' => $model->id ]);
            }
            else if (!\Yii::$app->request->isPost)
            {
                $model->load($_GET);
            }
        }
        catch (\Exception $e)
        {
            $msg = (isset($e->errorInfo[ 2 ])) ? $e->errorInfo[ 2 ] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        return $this->render('create', [ 'model' => $model ]);
    }


    /**
     * Deletes an existing Menu model.
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

            return $this->redirect("index");
        }
        catch (\Exception $e)
        {
            $msg = (isset($e->errorInfo[ 2 ])) ? $e->errorInfo[ 2 ] : $e->getMessage();
            \Yii::$app->getSession()
                      ->setFlash('error', $msg);

            return $this->redirect(yii\helpers\Url::previous());
        }
    }


    /**
     * Finds the Menu model based on its primary $id value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Menu the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menu::findOne([ 'id' => $id ])) !== null)
        {
            return $model;
        }
        else
        {
            throw new HttpException(404, Yii::t('backend', 'The requested page does not exist.'));
        }
    }


    /**
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDeleteMenuItem($id, $children = 0)
    {
        /** @var MenuItem $model */
        $model = MenuItem::findOne($id);

        if ($children == 1)
        {
            $msg = Yii::t('backend', 'This menu has children. For secutiry reasons, remove the children first please.');
            \Yii::$app->getSession()
                      ->setFlash('deleteMenuItemError', $msg);

            return $this->redirect([ '/menu/update', 'id' => $model->menu_id ]);
        }
        try
        {
            $model->delete();
        }
        catch (\Exception $e)
        {
            $msg = (isset($e->errorInfo[ 2 ])) ? $e->errorInfo[ 2 ] : $e->getMessage();
            \Yii::$app->getSession()
                      ->setFlash('error', $msg);

            return $this->redirect([ '/menu/update', 'id' => $model->menu_id ]);
        }

        return $this->redirect([ '/menu/update', 'id' => $model->menu_id ]);
    }


    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new MenuSearch;
        $dataProvider = $searchModel->search($_GET);

        Tabs::clearLocalStorage();

        yii\helpers\Url::remember();
        \Yii::$app->session[ '__crudReturnUrl' ] = null;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }


    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws HttpException
     */
    public function actionMenuItem($id)
    {
        /** @var MenuItem $model */
        $model = MenuItem::findOne($id);

        $model->setScenario("update");
        /** @var MenuItemTranslation $translation */
        $translation = MenuItemTranslation::findOne([ 'menu_item_id' => $id, 'language' => \Yii::$app->language ]);

        // SAVE THE MODEL.
        if (\Yii::$app->request->isPost && $model->load($_POST) && $translation->load($_POST))
        {
            if ($_POST[ 'menu-item-link' ] == 'content')
            {
                $translation->link = "";
            }
            else if ($_POST[ 'menu-item-link' ] == 'custom')
            {
                $model->content_id = 0;
            }
            if ($model->save() && $translation->save())
            {
                return $this->redirect([ '/menu/update', 'id' => $model->menu_id ]);
            }
        }

        // DISPLAY THE MODAL.
        return $this->renderAjax('_menu_item_form', [
            'model'       => $model,
            'translation' => $translation,
        ]);
    }


    /**
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @param $menu_id
     *
     * @return mixed
     * @throws \Exception
     */
    public function actionMenuItemCreate($menu_id)
    {
        /** @var MenuItem $model */
        $model          = new MenuItem();
        $model->menu_id = $menu_id;

        /** @var MenuItemTranslation $translation */
        $translation           = new MenuItemTranslation();
        $translation->language = \Yii::$app->language;

        if ($model->load($_POST) && $translation->load($_POST))
        {
            if (isset($translation->link) && strlen($translation->link) > 0)
            {
                $model->content_id = 0;
            }
            if ($model->save())
            {
                // Save the order of this menu.
                $query        = MenuItem::find()
                                        ->where([
                                            'menu_id' => $menu_id,
                                            'parent'  => $model->parent,
                                        ]);
                $model->order = $query->count();
                $model->save();

                $translation->menu_item_id = $model->id;

                if ($translation->save())
                {
                    // Save in other languages.
                    foreach (Locale::getAllKeys(true, false) as $language)
                    {
                        $t               = new MenuItemTranslation();
                        $t->menu_item_id = $model->id;
                        $t->language     = $language;
                        $t->title        = strtoupper($language) . " " . $translation->title;
                        $t->link         = "";
                        $t->created_at   = time();
                        $t->updated_at   = time();
                        $t->save(false);
                    }

                    return $this->redirect([ '/menu/update', 'id' => $menu_id ]);
                }
                else
                {
                    $translation->delete();
                    $model->delete();
                }
            }

            else
            {
                $translation->delete();
                $model->delete();
            }
        }

        elseif (!\Yii::$app->request->isPost)
        {
            $model->load($_GET);
        }

        return $this->redirect([ '/menu/update', 'id' => $menu_id ]);
    }


    public function actionMoveDown($id)
    {
        /** @var MenuItem $model */
        $model = MenuItem::findOne($id);

        if (isset($model))
        {

            $txn = MenuItem::getDb()
                           ->beginTransaction();

            try
            {

                /** @var MenuItem $other */
                $other = MenuItem::find()
                                 ->where([
                                     'menu_id' => $model->menu_id,
                                     'parent'  => $model->parent,
                                     'order'   => $model->order + 1,
                                 ])
                                 ->one();

                if (isset($other))
                {
                    $other->order = $other->order - 1;
                    $model->order = $model->order + 1;
                    $other->save();
                    $model->save();
                }

                MenuItem::sanitizeOrder($model->menu_id, $model->parent);

                $txn->commit();

            }
            catch (\Exception $e)
            {
                $txn->rollBack();
                throw $e;
            }

            return $this->redirect([ '/menu/update', 'id' => $model->menu_id ]);
        }
        else
        {
            throw new HttpException(404, Yii::t('backend', 'The requested page does not exist.'));
        }
    }


    /**
     * @param $id
     *
     * @return \yii\web\Response
     * @throws HttpException
     */
    public function actionMoveUp($id)
    {
        /** @var MenuItem $model */
        $model = MenuItem::findOne($id);

        if (isset($model))
        {
            // If current order is 1, return:
            if ($model->order != 1)
            {
                $txn = MenuItem::getDb()
                               ->beginTransaction();

                try
                {

                    /** @var MenuItem $other */
                    $other = MenuItem::find()
                                     ->where([
                                         'menu_id' => $model->menu_id,
                                         'parent'  => $model->parent,
                                         'order'   => $model->order - 1,
                                     ])
                                     ->one();

                    if (isset($other))
                    {
                        $other->order = $other->order + 1;
                        $model->order = $model->order - 1;
                        $other->save();
                        $model->save();
                    }

                    MenuItem::sanitizeOrder($model->menu_id, $model->parent);

                    $txn->commit();

                }
                catch (\Exception $e)
                {
                    $txn->rollBack();
                    throw $e;
                }

            }

            return $this->redirect([ '/menu/update', 'id' => $model->menu_id ]);
        }
        else
        {
            throw new HttpException(404, Yii::t('backend', 'The requested page does not exist.'));
        }
    }


    /**
     * Updates an existing Menu model.
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
}
