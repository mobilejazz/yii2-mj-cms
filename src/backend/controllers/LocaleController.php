<?php

namespace mobilejazz\yii2\cms\backend\controllers;

use dmstr\bootstrap\Tabs;
use mobilejazz\yii2\cms\backend\models\search\LocaleSearch;
use mobilejazz\yii2\cms\backend\modules\i18n\controllers\MessageController;
use mobilejazz\yii2\cms\backend\modules\i18n\models\I18nMessage;
use mobilejazz\yii2\cms\backend\modules\i18n\models\I18nSourceMessage;
use mobilejazz\yii2\cms\common\AuthHelper;
use mobilejazz\yii2\cms\common\models\ComponentField;
use mobilejazz\yii2\cms\common\models\ContentComponent;
use mobilejazz\yii2\cms\common\models\ContentMetaTag;
use mobilejazz\yii2\cms\common\models\ContentRelationship;
use mobilejazz\yii2\cms\common\models\ContentSlug;
use mobilejazz\yii2\cms\common\models\ContentSource;
use mobilejazz\yii2\cms\common\models\Locale;
use mobilejazz\yii2\cms\common\models\Menu;
use mobilejazz\yii2\cms\common\models\MenuItemTranslation;
use mobilejazz\yii2\cms\common\models\User;
use mobilejazz\yii2\cms\common\models\WebForm;
use mobilejazz\yii2\cms\common\models\WebFormDetail;
use mobilejazz\yii2\cms\common\models\WebFormRow;
use mobilejazz\yii2\cms\common\models\WebFormRowField;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;

/**
 * LocaleController implements the CRUD actions for Locale model.
 */
class LocaleController extends Controller
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
        return $user->role === User::ROLE_ADMIN || $user->role === User::ROLE_TRANSLATOR;
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
                        'roles' => [ 'admin', 'translator' ],
                    ],
                    [
                        'allow'        => false,
                        'denyCallback' => AuthHelper::denyCallback(function ()
                        {
                            Yii::$app->session->setFlash('error',
                                Yii::t('backend', 'Sorry, only Administrators and Translators can edit/create/update Languages.'));

                        }),
                    ],
                ],
            ],
        ];
    }


    /**
     * Creates a new Locale model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model          = new Locale;
        $model->default = 0;
        $model->used    = 1;

        if (\Yii::$app->request->isPost)
        {
            try
            {
                $base = null;
                if (!empty($_POST[ 'Locale' ]) && !empty($_POST[ 'Locale' ][ 'base_lang' ]))
                {
                    $base = $_POST[ 'Locale' ][ 'base_lang' ];
                }

                if ($base == null)
                {
                    $model->addError('_exception', \Yii::t('backend', 'You need to define a base Language'));

                    return $this->render('create', [ 'model' => $model ]);
                }

                if ($model->load($_POST) && $model->save())
                {
                    $transaction = \Yii::$app->db->beginTransaction();
                    $new_lang    = Locale::getIdentifier($model);
                    try
                    {
                        // ========================================
                        // CREATE EVERYTHING THAT HAS THIS LANGUAGE
                        // 1 - Menu item translations.
                        // ========================================
                        /** @var Menu[] $menus */
                        $menus = Menu::find()
                                     ->all();
                        foreach ($menus as $menu)
                        {
                            foreach ($menu->menuItems as $item)
                            {
                                $t = new MenuItemTranslation();
                                /** @var MenuItemTranslation $current */
                                $current         = $item->getCurrentTranslation($base);
                                $t->menu_item_id = $item->id;
                                $t->language     = $new_lang;
                                $t->title        = $current->title;
                                $t->link         = strlen($current->link) > 0 ? $current->link : '';
                                $t->created_at   = time();
                                $t->updated_at   = time();
                                // Save without data validation.
                                $t->save(false);
                            }
                        }

                        // ========================================
                        // 2 - Content fields, slugs and content_meta_tags
                        // ========================================
                        /** @var ContentSource[] $content_source */
                        $content_source = ContentSource::find()
                                                       ->all();
                        foreach ($content_source as $content)
                        {
                            // ====== SLUG ===== //
                            $new_slug             = new ContentSlug();
                            $new_slug->content_id = $content->id;
                            $new_slug->language   = $new_lang;
                            // Get english as it can never be deleted.
                            $slug                 = $content->getCurrentSlug($base);
                            $new_slug->slug       = $slug->slug;
                            $new_slug->title      = $slug->title;
                            $new_slug->system     = true;
                            $new_slug->created_at = time();
                            $new_slug->updated_at = time();
                            $new_slug->save(false);

                            // ====== CONTENT COMPONENTS. DUPLICATE THE EXACT SAME STRUCTURE AS THE BASE LANGUAGE. ===== //
                            /** @var ContentComponent[] $components */
                            $components     = $content->getOrderedContentComponents($base);
                            $duplicated_ids = [];
                            foreach ($components as $component)
                            {
                                if (in_array($component->id, $duplicated_ids))
                                {
                                    continue;
                                }

                                /** @var ContentComponent[] $group */
                                $group = ContentComponent::getGroup($component);

                                $group_id = 0;
                                foreach ($group as $comp)
                                {
                                    $duplicated_ids[] = $comp->id;
                                    $first            = $comp->isFirstInGroup();
                                    /** @var ComponentField[] $fields */
                                    $fields = $comp->getOrderedComponentFields($base);

                                    $clone_component             = new ContentComponent();
                                    $clone_component->attributes = $comp->attributes;
                                    $clone_component->language   = $new_lang;
                                    $clone_component->created_at = time();
                                    $clone_component->updated_at = time();
                                    $clone_component->group_id   = $group_id;
                                    unset($comp->id);
                                    $clone_component->isNewRecord = true;
                                    $clone_component->save(false);

                                    if (count($group) > 1 && $first)
                                    {
                                        $group_id                  = $clone_component->id;
                                        $clone_component->group_id = $clone_component->id;
                                        $clone_component->save(false);
                                    }
                                    else if (count($group) > 1 && !$first)
                                    {
                                        // Don't touch anything.
                                    }
                                    else
                                    {
                                        $group_id = 0;
                                    }

                                    foreach ($fields as $field)
                                    {
                                        $clone_field               = new ComponentField();
                                        $clone_field->attributes   = $field->attributes;
                                        $clone_field->language     = $new_lang;
                                        $clone_field->created_at   = time();
                                        $clone_field->updated_at   = time();
                                        $clone_field->component_id = $clone_component->id;
                                        unset($clone_field->id);
                                        $clone_field->isNewRecord = true;
                                        $clone_field->save(false);
                                    }
                                }
                            }
                        }

                        // ========================================
                        // 3 - FORMS.
                        // ========================================
                        /** @var WebForm[] $forms */
                        $forms = WebForm::find()
                                        ->all();
                        foreach ($forms as $webForm)
                        {
                            /** @var WebFormDetail $currentDetails */
                            $currentDetails = $webForm->getCurrentDetails($base);
                            // ====== WF DETAILS. ===== //
                            $clone_details             = new WebFormDetail();
                            $clone_details->attributes = $currentDetails->attributes;
                            $clone_details->language   = $new_lang;
                            $clone_details->created_at = time();
                            $clone_details->updated_at = time();
                            unset($clone_details->id);
                            $clone_details->isNewRecord = true;
                            $clone_details->save(false);

                            // ====== WF ROW ===== //
                            /** @var WebFormRow[] $webFormRows */
                            $webFormRows = WebFormRow::find()
                                                     ->where([ 'web_form' => $webForm->id, 'language' => $base ])
                                                     ->all();

                            /** @var WebFormRow $row */
                            foreach ($webFormRows as $row)
                            {
                                $clone_row             = new WebFormRow;
                                $clone_row->attributes = $row->attributes;
                                $clone_row->language   = $new_lang;
                                $clone_row->created_at = time();
                                $clone_row->updated_at = time();
                                unset($clone_row->id);
                                $clone_row->isNewRecord = true;
                                $clone_row->save(false);

                                /** @var WebFormRowField[] $fields */
                                $fields = $row->getOrderedWebFormRowFields($base);
                                foreach ($fields as $field)
                                {
                                    $clone_form_field                = new WebFormRowField;
                                    $clone_form_field->web_form_row  = $clone_row->id;
                                    $clone_form_field->language      = $new_lang;
                                    $clone_form_field->type          = $field->type;
                                    $clone_form_field->order         = $field->order;
                                    $clone_form_field->required      = $field->required;
                                    $clone_form_field->is_sensitive  = $field->is_sensitive;
                                    $clone_form_field->name          = $field->name;
                                    $clone_form_field->placeholder   = $field->placeholder;
                                    $clone_form_field->hint          = $field->hint;
                                    $clone_form_field->error_message = $field->error_message;
                                    $clone_form_field->created_at    = time();
                                    $clone_form_field->updated_at    = time();
                                    $clone_form_field->save(false);
                                    assert($clone_form_field);
                                }
                            }
                        }

                        // ========================================
                        // 4 - i18n content.
                        // ========================================
                        // FIRST DUPLICATE ANY STRING ALREADY FOUND.
                        /** @var I18nSourceMessage[] $source_messages */
                        $source_messages = I18nSourceMessage::find()
                                                            ->all();
                        foreach ($source_messages as $sm)
                        {
                            try
                            {
                                $m              = new I18nMessage();
                                $m->id          = $sm->id;
                                $m->language    = Locale::getIdentifier($model);
                                $m->translation = null;
                                $m->save(false);
                            }
                            catch (\Exception $e)
                            {
                                // nothing to do here.
                            }
                        }

                        // ========================================
                        // 5 - DUPLICATE ANY STRINGS NOT PREVIOUSLY FOUND THROUGH A COMMAND.
                        // ========================================
                        if (!defined('STDOUT'))
                        {
                            define('STDOUT', fopen('/tmp/stdout', 'w'));
                        }

                        // Run script to extract and clean up all the yii:t calls
                        //extract messages command
                        $controller = new MessageController('message', Yii::$app);
                        $controller->runAction('extract', [ '@common/config/extract.php' ]);

                        //extract messages command end
                        // Try and commit the batch.
                        $transaction->commit();
                    }

                    catch (\Exception $e)
                    {
                        try
                        {
                            $transaction->commit();
                        }
                        catch (\Exception $e)
                        {
                            $transaction->rollBack();
                            throw $e;
                        }
                    }

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
        }

        return $this->render('create', [ 'model' => $model ]);
    }


    /**
     * Deletes an existing Locale model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @param string  $lang
     *
     * @return mixed
     */
    public function actionDelete($id, $lang)
    {
        if ($id == 1)
        {
            Yii::$app->getSession()
                     ->setFlash('error', Yii::t('backend', 'Attention. The default language can not be deleted.'));

            return $this->redirect(Url::previous());
        }
        try
        {
            $model = $this->findModel($id, $lang);

            // If this is the default and used language, set english as default and used.
            if ($model->isUsed() && $model->isDefault())
            {
                Locale::defaultToEnglish();
            }

            $model->delete();

            $rows = 0;
            // ========================================
            // REMOVE EVERYTHING THAT HAS THIS LANGUAGE
            // 1 - Menu item translations.
            // ========================================
            $rows += MenuItemTranslation::deleteAll([ 'language' => Locale::getIdentifier($model) ]);
            // ========================================
            // 2 - Content Components.
            // ========================================
            $rows += ContentComponent::deleteAll([ 'language' => Locale::getIdentifier($model) ]);
            // ========================================
            // 3 - Content slugs.
            // ========================================
            $rows += ContentSlug::deleteAll([ 'language' => Locale::getIdentifier($model) ]);
            // ========================================
            // 3 - Content meta tags.
            // ========================================
            $rows += ContentMetaTag::deleteAll([ 'language' => Locale::getIdentifier($model) ]);
            // ========================================
            // 4 - Content relationships.
            // ========================================
            $rows += ContentRelationship::deleteAll([ 'language' => Locale::getIdentifier($model) ]);
            // ========================================
            // 5 - i18m messages.
            // ========================================
            $rows += I18nMessage::deleteAll([ 'language' => Locale::getIdentifier($model) ]);
            // ========================================
            // 6 - Web Forms.
            // ========================================
            $rows += WebFormDetail::deleteAll([ 'language' => Locale::getIdentifier($model) ]);
            $rows += WebFormRow::deleteAll([ 'language' => Locale::getIdentifier($model) ]);
            $rows += WebFormRowField::deleteAll([ 'language' => Locale::getIdentifier($model) ]);

            Yii::$app->getSession()
                     ->setFlash('success', Yii::t('backend', "A total of {rows} translations have been deleted.", [ 'rows' => $rows ]));
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


    /**
     * Finds the Locale model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @param string  $lang
     *
     * @return Locale the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id, $lang)
    {
        if (($model = Locale::findOne([ 'id' => $id, 'lang' => $lang ])) !== null)
        {
            return $model;
        }
        else
        {
            throw new HttpException(404, Yii::t('backend', 'The requested page does not exist.'));
        }
    }


    /**
     * Lists all Locale models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new LocaleSearch;
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
     * Updates an existing Locale model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @param string  $lang
     *
     * @return mixed
     */
    public function actionUpdate($id, $lang)
    {
        $model = $this->findModel($id, $lang);

        if ($model->load($_POST) && $model->save())
        {
            // If the model is the default, make sure all other ones are NOT default.
            if ($model->default == 1)
            {
                /** @var Locale[] $locales */
                $locales = Locale::find()
                                 ->all();

                foreach ($locales as $l)
                {
                    if ($l->id == $id)
                    {
                        continue;
                    }
                    else
                    {
                        $l->default = 0;
                    }
                    $l->save();
                }
            }

            // Check if we need to change the language
            if (!$model->isUsed() && Locale::getIdentifier($model) == Yii::$app->language)
            {
                // GET THE DEFAULT LANGUAGE
                /** @var Locale $default */
                $default = Locale::findOne([ 'default' => true ]);

                return $this->redirect("/admin/site/set-locale?locale=" . Locale::getIdentifier($default));
            }

            return $this->redirect(Url::previous());
        }
        else
        {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Displays a single Locale model.
     *
     * @param integer $id
     * @param string  $lang
     *
     * @return mixed
     */
    public function actionView($id, $lang)
    {
        \Yii::$app->session[ '__crudReturnUrl' ] = Url::previous();
        Url::remember();
        Tabs::rememberActiveState();

        return $this->render('view', [
            'model' => $this->findModel($id, $lang),
        ]);
    }
}
