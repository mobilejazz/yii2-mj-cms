<?php

namespace mobilejazz\yii2\cms\backend\controllers;

use dmstr\bootstrap\Tabs;
use mobilejazz\yii2\cms\backend\models\search\ContentSourceSearch;
use mobilejazz\yii2\cms\backend\models\search\ContentSourceTrashedSearch;
use mobilejazz\yii2\cms\common\AuthHelper;
use mobilejazz\yii2\cms\common\models\ComponentField;
use mobilejazz\yii2\cms\common\models\Components;
use mobilejazz\yii2\cms\common\models\ContentComponent;
use mobilejazz\yii2\cms\common\models\ContentSlug;
use mobilejazz\yii2\cms\common\models\ContentSource;
use mobilejazz\yii2\cms\common\models\Fields;
use mobilejazz\yii2\cms\common\models\Locale;
use mobilejazz\yii2\cms\common\models\User;
use mobilejazz\yii2\cms\common\models\Views;
use Yii;
use yii\base\DynamicModel;
use yii\base\Model;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;

/**
 * ContentSourceController implements the CRUD actions for ContentSource model.
 */
class ContentSourceController extends Controller
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
        return $user->role === User::ROLE_ADMIN || $user->role === User::ROLE_EDITOR;
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
                    // EDITORS CAN ONLY EDIT THEIR OWN CONTENT.
                    [
                        'roles'         => [ 'editor' ],
                        'actions'       => [ 'update', 'delete' ],
                        'allow'         => false,
                        'matchCallback' => function ()
                        {
                            $id = $_REQUEST[ 'id' ];
                            /** @var ContentSource $content */
                            $content = ContentSource::findOne($id);

                            return $content->author_id !== Yii::$app->user->id;
                        },
                        'denyCallback'  => AuthHelper::denyCallback(function ()
                        {
                            Yii::$app->session->setFlash('error',
                                Yii::t('backend', 'You can not perform this action on content now created by you.'));

                        }),

                    ],
                    [
                        'actions'       => [ 'create', 'update', 'delete', 'bulk' ],
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
                        'roles' => [ 'admin', 'editor' ],
                    ],
                    [
                        'roles' => [ 'translator', 'user' ],
                        'allow' => false,
                    ],
                ],
            ],
        ];
    }


    /**
     * @param array $order ids of the components ordered by new order.
     *
     * @return string
     */
    public function actionOrderUpdate($order)
    {
        $order = json_decode($order);

        $s[] = null;
        /** @var ContentComponent $cmp */
        foreach ($order as $id)
        {
            $s[] = $this->findComponent($id);
        }

        $new_order = 1;
        foreach ($s as $cmp)
        {
            if (isset($cmp) && $cmp != null)
            {
                ContentComponent::setGroupOrder($cmp, $new_order++);
            }
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'msg'  => \Yii::t('backend', 'The list has been re-ordered'),
            'code' => 100,
        ];
    }


    /**
     * @param $id
     *
     * @return ContentComponent null|static
     */
    protected function findComponent($id)
    {
        return ContentComponent::findOne($id);
    }


    public function actionFieldUpdate()
    {
        if (\Yii::$app->request->isPost)
        {
            $id    = \Yii::$app->request->post("field");
            $val   = \Yii::$app->request->post("value");
            $field = $this->findField($id);

            if (is_array($val))
            {
                $val = $val[ 'path' ];
            }

            $field->text = $val;

            $field->save(false);
        }
    }


    /**
     * @param $id
     *
     * @return ComponentField null|static
     */
    protected function findField($id)
    {
        return ComponentField::findOne($id);
    }


    /**
     * Adds a field that is repeatable to a given view of content.
     * The field should be placed right under its parents order given that
     * the parent is the field that corresponds to the parameter $id.
     *
     * @param $id
     *
     * @return ContentSourceController|\yii\console\Response|Response
     */
    public function actionAddField($id)
    {
        /** @var ComponentField $field */
        $field = ComponentField::findOne($id);

        $component_id = $field->component_id;

        // First increase the order of the following items.
        /** @var ComponentField $fields_to_modify */
        $fields_to_modify = ComponentField::find()
                                          ->where([
                                              'component_id' => $component_id,
                                              'language'     => $field->language,
                                          ])
                                          ->andWhere('`order` > ' . $field->order)
                                          ->orderBy([ 'updated_at' => SORT_DESC ])
                                          ->all();

        foreach ($fields_to_modify as $f)
        {
            $f->order = $f->order + 1;
            $f->save(false);
        }

        // Now duplicate the content field and save it into the database.
        $new_field               = new ComponentField();
        $new_field->component_id = $field->component_id;
        $new_field->language     = $field->language;
        $new_field->order        = $field->order + 1;
        $new_field->type         = $field->type;
        $new_field->repeatable   = $field->repeatable;
        $new_field->required     = $field->required;
        $new_field->is_child     = true;
        $new_field->text         = Fields::getDefaultValue(null, $field->type);
        $new_field->save(false);

        // Make sure the previous field is no longer repeatable.
        $field->repeatable = false;
        $field->save(false);

        // Return to the previous page.
        return Yii::$app->response->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }


    /**
     * Emits a bulk action from the grid view.
     * @return Response
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
                /** @var User $user */
                $user = Yii::$app->user->getIdentity();
                // Array with items
                $contents = ContentSource::find()
                                         ->where([ 'in', 'id', $selection ])
                                         ->all();

                // Check ownership
                /** @var ContentSource $content */
                foreach ($contents as $content)
                {
                    if ($content->author_id !== $user->id && $user->role == User::ROLE_EDITOR)
                    {
                        Yii::$app->getSession()
                                 ->setFlash('error', Yii::t('backend', 'You are not allowed to perform bulk tasks on contents not created by you.'));

                        return $this->redirect(Url::previous());
                    }
                }

                /** @var  ContentSource $e */
                foreach ($contents as $e)
                {
                    switch ($action)
                    {
                        case 'publish':
                            $e->status = ContentSource::STATUS_PUBLISHED;
                            $e->save();
                            break;
                        case 'private':
                            $e->status = ContentSource::STATUS_PRIVATE_CONTENT;
                            $e->save();
                            break;
                        case 'draft':
                            $e->status = ContentSource::STATUS_DRAFT;
                            $e->save();
                            break;
                        case 'delete':
                            $e->delete();
                            break;
                        case 'restore':
                            $e->status = ContentSource::STATUS_DRAFT;
                            $e->save();
                            break;
                    }
                }
            }
        }

        return $this->redirect(Url::previous());
    }


    /**
     * Deletes a component if it can be deleted.
     *
     * @param $id
     *
     * @return array|ContentSourceController
     * @throws \Exception
     */
    public function actionComponentDelete($id)
    {
        // First find the Component to delete.
        $component = $this->findComponent($id);
        $title     = $component->title;
        $content   = $component->content;
        $arr       = [];
        if ($component->id != $component->group_id)
        {
            array_push($arr, $component->group_id);
        }
        $component->delete();

        // Sanitize order of the components.
        ContentComponent::sanitizeOrder($content);

        \Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'id'           => $component->content_id,
            'opened_boxes' => $arr,
            'msg'          => \Yii::t('backend', 'The component {title} has been successfully removed', [ 'title' => $title, ]),
            'code'         => 100,
        ];
    }


    /**
     * Action. If the component is allowed to be duplicated, duplicate.
     *
     * @param $id
     *
     * @return array|ContentSourceController
     */
    public function actionComponentDuplicate($id)
    {
        $component = $this->findComponent($id);

        $cmp = null;

        // if not repeatable, continue.
        if ($component->isRepeatable())
        {
            $type   = $component->type;
            $locale = Yii::$app->language;
            $cmp    = ContentComponent::create($type, $component->content, $locale, $component->order, [ 'repeatable' => true, ], $component->id,
                false);
        }

        $arr = [];
        if ($cmp->group_id != $cmp->id)
        {
            array_push($arr, $cmp->group_id);
        }
        array_push($arr, $cmp->id);

        \Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'id'           => $cmp->content_id,
            'opened_boxes' => $arr,
            'msg'          => \Yii::t('backend', 'The component has been successfully duplicated.'),
            'code'         => 100,
        ];
    }


    public function actionQuickView($id)
    {
        $model      = $this->findModel($id);
        $lang       = Yii::$app->language;
        $slug       = $model->getCurrentSlug($lang);
        $components = $model->getOrderedContentComponentsByGroup($lang);

        if (Yii::$app->request->isAjax)
        {
            return $this->renderAjax('_quick-view', [
                'lang'       => $lang,
                'slug'       => $slug,
                'components' => $components,
            ]);
        }

        return Yii::$app->response->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }


    /**
     * Finds the ContentSource model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return ContentSource the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ContentSource::findOne($id)) !== null)
        {
            return $model;
        }
        else
        {
            throw new HttpException(404, Yii::t('backend', 'The requested page does not exist.'));
        }
    }


    /**
     * Deletes a whole ContentComponent Group.
     *
     * @param $id
     *
     * @return array|ContentSourceController
     * @throws \Exception
     */
    public function actionComponentDeleteGroup($id)
    {
        // First find the Component to delete.
        $component = $this->findComponent($id);
        $title     = $component->title;
        $content   = $component->content;

        /** @var ContentComponent[] $group */
        $group = ContentComponent::getGroup($component);
        foreach ($group as $component)
        {
            $component->delete();
        }

        // Sanitize order of the components.
        ContentComponent::sanitizeOrder($content);

        \Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'id'           => $component->content_id,
            'opened_boxes' => null,
            'msg'          => \Yii::t('backend', 'The component group {title} has been successfully removed', [ 'title' => $title ]),
            'code'         => 100,
        ];
    }


    /**
     * Moves the given component up within its own group.
     *
     * @param $id
     *
     * @return ContentSourceController|\yii\console\Response|Response
     */
    public function actionComponentMoveWithinGroupUp($id)
    {
        // Find the component.
        $component = $this->findComponent($id);
        // Change order and move up the given component.
        ContentComponent::moveWithinGroupUp($component);

        // Return to the previous page.
        return Yii::$app->response->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }


    /**
     * Moves the given component down within its own group.
     *
     * @param $id
     *
     * @return ContentSourceController|\yii\console\Response|Response
     */
    public function actionComponentMoveWithinGroupDown($id)
    {
        // Find the component.
        $component = $this->findComponent($id);
        // Change order and move up the given component.
        ContentComponent::moveWithinGroupDown($component);

        // Return to the previous page.
        return Yii::$app->response->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }


    /**
     * Creates a new ContentSource model.
     * If creation is successful, the browser will be redirected to the 'update' page.
     * @return mixed
     */
    public function actionCreate()
    {
        /** @var ContentSource $model */
        $model = new ContentSource;
        /** @var ContentSlug $slug */
        $slug           = new ContentSlug;
        $slug->language = Yii::$app->language;
        if ($model->load($_POST) && $slug->load($_POST))
        {
            if ($model->save())
            {
                $slug->content_id = $model->id;
                $slug->system     = true;
                if ($slug->save())
                {
                    // ===== CREATE ALL THE CONTENT FOR EACH LANGUAGE ===== //
                    foreach (Locale::getAllKeys() as $locale)
                    {
                        if ($locale != Yii::$app->language)
                        {
                            // ====== SLUG ===== //
                            $new_slug             = new ContentSlug();
                            $new_slug->content_id = $model->id;
                            $new_slug->language   = $locale;
                            $new_slug->slug       = $slug->slug;
                            $new_slug->title      = $slug->title;
                            $new_slug->system     = true;
                            $new_slug->created_at = time();
                            $new_slug->updated_at = time();
                            $new_slug->save();
                        }

                        // ====== CONTENT FIELDS AND TRANSLATIONS ===== //
                        $view       = Views::getView($model->view);
                        $order      = 0;
                        $components = $view[ 'components' ];
                        foreach ($components as $component)
                        {
                            $order = ContentComponent::create($component, $model, $locale, $order + 1, null)->order;
                        }
                    }

                    return $this->redirect(Url::previous());
                }
                else
                {
                    $slug->delete();
                    $model->delete();
                }
            }
            else
            {
                $slug->delete();
                $model->delete();
            }
        }

        elseif (!\Yii::$app->request->isPost)
        {
            $model->load($_GET);
            $slug->load($_GET);
        }

        return $this->render('update', [
            'model' => $model,
            'slug'  => $slug,
        ]);
    }


    /**
     * Add a new component dynamically.
     *
     * @param $id
     * @param $order
     *
     * @return mixed
     * @throws HttpException
     */
    public function actionAddComponent($id, $order)
    {
        $model     = $this->findModel($id);
        $lang      = Yii::$app->language;
        $component = new ContentComponent();

        if (Yii::$app->request->isPost)
        {
            $type = $_POST[ 'ContentComponent' ][ 'type' ];

            // Actually create the component
            $component = ContentComponent::create($type, $model, $lang, $order + 1, null);

            \Yii::$app->response->format = Response::FORMAT_JSON;

            $arr = [];
            if ($component->group_id != $component->id)
            {
                array_push($arr, $component->group_id);
            }
            array_push($arr, $component->id);

            return [
                'id'           => $model->id,
                'opened_boxes' => $arr,
                'code'         => 100,
                'msg'          => \Yii::t('backend', 'Component successfully added to the content'),
            ];
        }
        else
        {
            if (Yii::$app->request->isAjax)
            {
                return $this->renderAjax('_add-component', [
                    'model'     => $model,
                    'component' => $component,
                    'language'  => $lang,
                ]);
            }
            else
            {
                return $this->render('_add-component', [
                    'model'     => $model,
                    'component' => $component,
                    'language'  => $lang,
                ]);
            }
        }
    }


    public function actionDisplayComponent($id, $single = false)
    {
        $component  = $this->findComponent($id);
        $components = ContentComponent::getGroup($component);

        return $this->renderAjax("_single-component", [
            'components' => !$single ? $components : [ $component ],
            '$key'       => $component->order - 1,
        ]);
    }


    /**
     * Add a new form to this content dynamically.
     *
     * @param $id
     * @param $order
     *
     * @return string
     */
    public function actionAddForm($id, $order)
    {
        $model = $this->findModel($id);
        $lang  = Yii::$app->language;
        $field = new ComponentField();

        if (Yii::$app->request->isPost)
        {
            $field->load($_POST);

            // CREATE AND SAVE THE COMPONENT HOLDING THIS FORM.
            /** @var ContentComponent $component */
            $component = ContentComponent::create('form', $model, $lang, $order + 1, null);

            /** @var ComponentField $f */
            $f       = $component->getOrderedComponentFields($lang)[ 0 ];
            $f->text = $field->text;
            $f->save();

            \Yii::$app->response->format = Response::FORMAT_JSON;

            return [
                'opened_boxes' => [ $component->id ],
                'id'           => $model->id,
                'code'         => 100,
                'msg'          => \Yii::t('backend', 'Form successfully added to the content'),
            ];
        }
        else
        {
            if (Yii::$app->request->isAjax)
            {
                return $this->renderAjax('_add-form', [
                    'model'    => $model,
                    'language' => $lang,
                    'field'    => $field,
                ]);
            }
            else
            {
                return $this->render('_add-form', [
                    'model'    => $model,
                    'language' => $lang,
                    'field'    => $field,
                ]);
            }
        }
    }


    /**
     * Deletes an existing ContentSource model.
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

        return $this->redirect([ 'index' ]);
    }


    public function actionDeleteField($id)
    {
        $field = $this->findField($id);

        $order        = ($field->order - 1);
        $component_id = $field->component_id;
        $language     = $field->language;
        $type         = $field->type;

        // Get previous content field and set it to repeatable.
        if (ComponentField::findOne([
            'component_id' => $component_id,
            'language'     => $language,
            'type'         => $type,
            'order'        => $order,
        ])
                          ->setRepeatable(true)
        )
        {
            try
            {
                $field->delete();
            }
            catch (\Exception $e)
            {
                $msg = (isset($e->errorInfo[ 2 ])) ? $e->errorInfo[ 2 ] : $e->getMessage();
                \Yii::$app->getSession()
                          ->setFlash('error', $msg);
            }
        }

        // Return to the previous page.
        return Yii::$app->response->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }


    /**
     * Lists all ContentSource models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new ContentSourceSearch;
        $dataProvider = $searchModel->search($_GET);
        /** @var ContentSource[] $deleted */
        $deleted = ContentSource::findAll([ 'status' => ContentSource::STATUS_DELETED ]);
        Tabs::clearLocalStorage();
        Url::remember();
        \Yii::$app->session[ '__crudReturnUrl' ] = null;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
            'deleted'      => $deleted,
        ]);
    }


    /**
     * Displays the Trash of the ContentSource.
     */
    public function actionTrash()
    {
        $searchModel         = new ContentSourceTrashedSearch();
        $searchModel->status = ContentSource::STATUS_DELETED;
        $dataProvider        = $searchModel->search($_GET);
        Tabs::clearLocalStorage();
        Url::remember();
        \Yii::$app->session[ '__crudReturnUrl' ] = null;

        return $this->render('trash', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }


    /**
     * Restores a ContentSource back from the dead.
     *
     * @param $id
     *
     * @return ContentSourceController|\yii\console\Response|Response
     * @throws HttpException
     */
    public function actionRestore($id)
    {
        /** @var ContentSource $model */
        $model = $this->findModel($id);

        $model->publish(false);

        return Yii::$app->response->redirect("index");
    }


    /**
     * Makes the current model the homepage of the website.
     *
     * @param $id
     *
     * @return ContentSourceController|\yii\console\Response|Response
     * @throws HttpException
     */
    public function actionMakeHomepage($id)
    {
        $model = $this->findModel($id);
        $model->setAsHomePage();

        return Yii::$app->response->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }


    // http://192.168.99.100/admin/content-source/update-partial?id=88
    public function actionUpdatePartial($id)
    {
        $model      = $this->findModel($id);
        $components = $model->getOrderedContentComponents(Yii::$app->language);

        return $this->renderAjax("_content-view", [
            'model'        => $model,
            'components'   => $components,
            'field_errors' => null,
        ]);
    }


    /**
     * Updates an existing ContentSource model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model        = $this->findModel($id);
        $slug         = $model->getCurrentSlug(Yii::$app->language);
        $components   = $model->getOrderedContentComponents(Yii::$app->language);
        $field_errors = null;

        if (Yii::$app->request->isPost)
        {
            // $model->publish_date_string = $_POST[ 'ContentSource' ][ 'publish_date_string' ];
            // $model->published_at        = strtotime($model->publish_date_string);

            if ($model->load($_POST))
            {
                $model->save();
            }
            if ($slug->load($_POST))
            {
                // CHECK IF SLUG HAS CHANGED, IF SO, CREATE A NEW ONE.
                $old_s = $model->getCurrentSlug(Yii::$app->language);
                if (strcmp($slug->slug, $old_s->slug) != 0)
                {
                    // ====== SLUG ===== //
                    $new_slug             = new ContentSlug();
                    $new_slug->content_id = $model->id;
                    $new_slug->language   = Yii::$app->language;
                    $new_slug->slug       = $slug->slug;
                    $new_slug->title      = $slug->title;
                    $new_slug->system     = true;
                    $new_slug->created_at = time();
                    $new_slug->updated_at = time();
                    $new_slug->save();
                }
                // Else only the title has changed.
                else
                {
                    $slug->save();
                }
            }
            if (Model::loadMultiple($components, $_POST))
            {
                /** @var ContentComponent $component */
                foreach ($components as $component)
                {
                    // FIXME: The following two lines are required because for some reason the name of the component changes from time to time (need to look into this).
                    $comp             = Components::getComponent($component->type);
                    $component->title = $comp[ 'name' ];

                    // Save the component.
                    $component->save(false);

                    /** @var ComponentField[] $component_fields */
                    $component_fields = $component->getComponentFields()
                                                  ->all();

                    foreach ($component_fields as $field)
                    {
                        $text = $_POST[ 'ComponentField' ][ $field->id ][ 'text' ];

                        // If the value gotten from the form is an array,
                        // we are most likely dealing with an image,
                        // so save the path.
                        if (is_array($text))
                        {
                            $text = $text[ 'path' ];
                        }

                        // This is important: CREATE A DYNAMIC MODEL TO VALIDATE THE DATA GIVEN THE FIELD RULES.
                        $field_model = DynamicModel::validateData(compact('text'), Fields::getRules($field->type, $component->type));

                        // Validation has failed.
                        if ($field_model->hasErrors())
                        {
                            $field_errors[ $field->id ] = $field_model->errors;

                            $component->addError($field->id, $field_model->errors );

                        }
                        // Validation has succeeded.
                        else
                        {
                            $field->text = $text;
                            $field->save(false);
                        }
                    }
                }
            }
            // RELOAD EVERYTHING
            $model      = $this->findModel($id);
            $slug       = $model->getCurrentSlug(Yii::$app->language);
            //$components = $model->getOrderedContentComponents(Yii::$app->language);
        }

        if (\Yii::$app->request->isAjax)
        {
            return $this->renderAjax('update', [
                'model'        => $model,
                'components'   => $components,
                'slug'         => $slug,
                'field_errors' => $field_errors,
            ]);
        }

        return $this->render('update', [
            'model'        => $model,
            'components'   => $components,
            'slug'         => $slug,
            'field_errors' => $field_errors,
        ]);
    }
}
