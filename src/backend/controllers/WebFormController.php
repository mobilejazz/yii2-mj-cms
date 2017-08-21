<?php

namespace mobilejazz\yii2\cms\backend\controllers;

use mobilejazz\yii2\cms\backend\models\search\WebFormSearch;
use mobilejazz\yii2\cms\backend\models\search\WebFormSubmissionSearch;
use mobilejazz\yii2\cms\common\AuthHelper;
use mobilejazz\yii2\cms\common\models\Locale;
use mobilejazz\yii2\cms\common\models\User;
use mobilejazz\yii2\cms\common\models\WebForm;
use mobilejazz\yii2\cms\common\models\WebFormDetail;
use mobilejazz\yii2\cms\common\models\WebFormRow;
use mobilejazz\yii2\cms\common\models\WebFormRowField;
use mobilejazz\yii2\cms\common\models\WebFormSubmission;
use yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;

/**
 * WebFormController implements the CRUD actions for WebForm model.
 */
class WebFormController extends Controller
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
                    // EDITORS CAN ONLY EDIT THEIR OWN WEB-FORMS.
                    [
                        'roles'         => [ 'editor' ],
                        'actions'       => [ 'update', 'delete' ],
                        'allow'         => false,
                        'matchCallback' => function ()
                        {
                            $id = $_REQUEST[ 'id' ];
                            /** @var WebForm $form */
                            $form = WebForm::findOne($id);

                            return $form->author_id !== Yii::$app->user->id;
                        },
                        'denyCallback'  => AuthHelper::denyCallback(function ()
                        {
                            Yii::$app->session->setFlash('error',
                                Yii::t('backend', 'You can not perform this action on a WebForm now created by you.'));
                        }),

                    ],
                    [
                        'actions'       => [
                            'create',
                            'update',
                            'delete',
                            'add-row',
                            'add-field',
                            'row-delete',
                            'field-delete',
                            'bulk',
                            'order-update'
                        ],
                        'allow'         => false,
                        'matchCallback' => function ($rule, $action)
                        {
                            $writeLockConfig = ArrayHelper::getValue(\Yii::$app->params, 'writeLock', ['production']);
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
                        'roles' => [ 'admin', 'editor' ],
                    ],
                    [
                        'roles'        => [ 'translator', 'user' ],
                        'allow'        => false,
                        'denyCallback' => AuthHelper::denyCallback(function ()
                        {
                            Yii::$app->session->setFlash('error',
                                Yii::t('backend', 'Sorry, only Administrators and Editors can perform this action.'));
                        }),
                    ],
                ],
            ],
        ];
    }


    /**
     * Lists all WebForm models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new WebFormSearch;
        $dataProvider = $searchModel->search($_GET);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }


    /**
     * Creates a new WebForm model.
     * If creation is successful, the browser will be redirected to the 'update' page.
     * @return mixed
     */
    public function actionCreate()
    {
        /** @var WebForm $model */
        $model = new WebForm;
        /** @var WebFormDetail $details */
        $details           = new WebFormDetail();
        $details->language = Yii::$app->language;

        try
        {
            if ($details->load($_POST))
            {
                if ($model->save())
                {
                    $details->web_form = $model->id;
                    $details->mail     = json_encode($details->emails, JSON_PRETTY_PRINT);
                    $details->validate();
                    if ($details->save())
                    {
                        // ===== CREATE A REPLICA OF THE FORM IN EACH LANGUAGE ===== //
                        foreach (Locale::getAllKeys() as $locale)
                        {
                            if ($locale != Yii::$app->language)
                            {
                                // ====== DETAILS ===== //
                                $new_details              = new WebFormDetail();
                                $new_details->web_form    = $model->id;
                                $new_details->language    = $locale;
                                $new_details->title       = $details->title;
                                $new_details->mail        = $details->mail;
                                $new_details->send_mail   = $details->send_mail;
                                $new_details->description = $details->description;
                                $new_details->script      = $details->script;
                                $new_details->message     = $details->message;
                                $new_details->created_at  = time();
                                $new_details->updated_at  = time();
                                $new_details->save();
                            }
                        }

                        return $this->redirect(Url::to([
                            'update',
                            'id' => $model->id,
                        ]));
                    }
                    else
                    {
                        $details->delete();
                        $model->delete();
                        Yii::$app->session->setFlash("error",
                            Yii::t('backend', 'It looks like there has been a problem while saving the details of this Web Form.'));
                    }
                }
                else
                {
                    $details->delete();
                    $model->delete();
                    Yii::$app->session->setFlash("error", Yii::t('backend', 'It looks like there has been a problem while saving the Web Form.'));
                }
            }
            elseif (!\Yii::$app->request->isPost)
            {
                $model->load($_GET);
                $details->load($_GET);
            }
        }
        catch (\Exception $e)
        {
            $msg = (isset($e->errorInfo[ 2 ])) ? $e->errorInfo[ 2 ] : $e->getMessage();
            Yii::$app->session->setFlash("error", $msg);
        }

        return $this->render('update', [
            'model'   => $model,
            'details' => $details,
        ]);
    }


    /**
     * Updates an existing WebForm model.
     *
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        /** @var WebForm $model */
        $model = $this->findModel($id);
        /** @var WebFormDetail $details */
        $details         = $model->getCurrentDetails(Yii::$app->language);
        $details->emails = json_decode($details->mail);
        /** @var WebFormRow[] $rows */
        $rows = $model->getOrderedWebFormRows(Yii::$app->language);

        // Field errors.
        /** @var  $field_errors */
        $field_errors = null;

        // If this is a POST handle information.
        if (Yii::$app->request->isPost)
        {
            // There is no need to actually load the model ($model->load($_POST)
            // since no important information is saved/updated on them.
            // Never the less, we will do so just in case we add information
            // to the model in the future.
            if ($model->load($_POST))
            {
                $model->save();
            }

            // Load and save the changed details if needed.
            if ($details->load($_POST))
            {
                $details->mail = json_encode($details->emails, JSON_PRETTY_PRINT);
                $details->save();
            }

            // Load and save the changed rows and fields if needed.
            if (WebFormRow::loadMultiple($rows, $_POST))
            {
                /** @var WebFormRow $row */
                foreach ($rows as $row)
                {
                    $row->save();

                    // Again, like with the ContentSourceController update method
                    // We have to come up with this uglier solution since
                    // there is no nicer way to do this, and by this I mean:
                    // Loading child models that at their time are childs of another model.
                    // In this case: Form > Row > Fields. There is no way with Yii
                    // To validate and load the Fields except from this one:
                    /** @var WebFormRowField[] $raw_fields */
                    $raw_fields = $row->getOrderedWebFormRowFields(Yii::$app->language);

                    foreach ($raw_fields as $field)
                    {
                        // Name
                        $name = $_POST[ 'WebFormRowField' ][ $field->id ][ 'name' ];
                        // type
                        $type = $_POST[ 'WebFormRowField' ][ $field->id ][ 'type' ];
                        // placeholder
                        $placeholder = $_POST[ 'WebFormRowField' ][ $field->id ][ 'placeholder' ];
                        // hint
                        $hint = $_POST[ 'WebFormRowField' ][ $field->id ][ 'hint' ];
                        // required
                        $required = $_POST[ 'WebFormRowField' ][ $field->id ][ 'required' ];
                        // Is this sensitive?
                        $sensitive = $_POST[ 'WebFormRowField' ][ $field->id ][ 'is_sensitive' ];
                        // Error Message
                        $message = $_POST[ 'WebFormRowField' ][ $field->id ][ 'error_message' ];

                        $field->type          = $type;
                        $field->placeholder   = $placeholder;
                        $field->hint          = $hint;
                        $field->required      = $required;
                        $field->is_sensitive  = $sensitive;
                        $field->name          = $name;
                        $field->error_message = $message;
                        if ($field->hasErrors())
                        {
                            $field_errors[ $field->id ] = $field->errors;
                        }
                        else
                        {
                            $field->save(false);
                        }
                    }
                }
            }
        }

        return $this->render('update', [
            'model'        => $model,
            'details'      => $details,
            'rows'         => $rows,
            'field_errors' => $field_errors,
        ]);
    }


    /**
     * Finds the WebForm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return WebForm the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WebForm::findOne($id)) !== null)
        {
            return $model;
        }
        else
        {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }


    /**
     * Add a new row dynamically.
     *
     * @param integer $id
     * @param integer $order
     *
     * @return $this
     * @throws HttpException
     */
    public function actionAddRow($id, $order = null)
    {
        $lang = Yii::$app->language;

        // Add a row to this $model
        WebFormRow::create($id, $lang, $order + 1);

        return Yii::$app->response->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }


    /**
     * Add a new field to a given row dynamically.
     *
     * @param integer $id
     * @param integer $order
     *
     * @return $this
     */
    public function actionAddField($id, $order)
    {
        $lang = Yii::$app->language;

        // Add a field to this row.
        $field = WebFormRowField::create($id, $lang, $order + 1);

        return Yii::$app->response->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }


    public function actionRowDelete($id)
    {
        /** @var WebFormRow $row */
        $row = $this->findRow($id);
        /** @var WebForm $form */
        $form = $row->webForm;
        $row->delete();
        // Sanitize order of the rows left.
        WebFormRow::sanitizeOrder($form);

        // Return to the previous page.
        return Yii::$app->response->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }


    /**
     * @param $id
     *
     * @return WebFormRow null|static
     */
    protected function findRow($id)
    {
        return WebFormRow::findOne($id);
    }


    public function actionFieldDelete($id)
    {
        /** @var WebFormRowField $field */
        $field = $this->findField($id);
        /** @var WebFormRow $row */
        $row = $field->webFormRow;
        $field->delete();
        // Sanitize order of the fields left.
        WebFormRowField::sanitizeOrder($row);

        // Return to the previous page.
        return Yii::$app->response->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }


    protected function findField($id)
    {
        return WebFormRowField::findOne($id);
    }


    /**
     * Deletes an existing WebForm model.
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
                      ->addFlash('error', $msg);

            return $this->redirect(Url::previous());
        }

        return $this->redirect([ 'index' ]);
    }


    /**
     * @return \yii\web\Response
     */
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
                    /** @var WebForm $e */
                    $e = WebForm::findOne($item);
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
     * Displays the submissions for a given form.
     * @return string
     * @internal param $id
     */
    public function actionSubmissions()
    {
        $searchModel  = new WebFormSubmissionSearch;
        $dataProvider = $searchModel->search($_GET);

        $name = WebFormDetail::findOne([ 'id' => $searchModel->web_form, ])->title;

        return $this->render('submissions', [
            'id'           => $searchModel->web_form,
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
            'name'         => $name,
        ]);
    }


    public function actionExportAllSubmissions($id)
    {
        $submissions = WebFormSubmission::find()
                                        ->where([
                                            'web_form' => $id,
                                        ])
                                        ->all();

        $dataProvider = WebFormSubmission::getDataToArray($id, $submissions);

        return $this->render('_export', [
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionExportAllUnexportedSubmissions($id)
    {
        assert($id);

        return $this->render('_export', [
            'models'  => null,
            'columns' => [],
            'headers' => [],
        ]);
    }


    /**
     * @param array $order ids of the compnoents ordered by new order.
     *
     * @return string
     */
    public function actionOrderUpdate($order)
    {
        $order = json_decode($order);

        /** @var WebFormRow $row */
        foreach ($order as $index => $id)
        {
            $row   = $this->findRow($id);
            $order = $index + 1;
            WebFormRow::setOrder($row, $order);
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'msg'  => \Yii::t('backend', 'The list has been re-ordered'),
            'code' => 100,
        ];
    }
}
