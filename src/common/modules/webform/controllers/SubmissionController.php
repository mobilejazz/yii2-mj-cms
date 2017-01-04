<?php
namespace mobilejazz\yii2\cms\common\modules\webform\controllers;

use mobilejazz\yii2\cms\common\models\Fields;
use mobilejazz\yii2\cms\common\models\Locale;
use mobilejazz\yii2\cms\common\models\WebForm;
use mobilejazz\yii2\cms\common\models\WebFormDetail;
use mobilejazz\yii2\cms\common\models\WebFormRow;
use mobilejazz\yii2\cms\common\models\WebFormRowField;
use mobilejazz\yii2\cms\common\models\WebFormSubmission;
use yii;
use yii\base\DynamicModel;
use yii\console\Response as ConsoleResponse;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SubmissionController extends Controller
{

    /**
     * Action that controls what happens upon
     * [[ WebForm ]] submission.
     * @return SubmissionController|string|ConsoleResponse|Response
     */
    public function actionSubmit()
    {
        // Check if we are in a Post. If not, this action should not be allowed.
        // However this should never happen since get access is disabled in
        // the behaviors() method in this controller.
        if (!Yii::$app->request->isPost)
        {
            // Return to the previous page or home page if not found.
            return Yii::$app->response->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        }

        // Check if we have received any field information at all.
        if (!isset($_POST[ 'WebFormRowField' ]))
        {
            Yii::$app->session->setFlash('error', Yii::t('app', 'It looks like the form you are trying to fill has no Fields.
                If you think this is a server error, please let us know.'));

            return Yii::$app->response->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        }
        // VALIDATE THE DATA.
        $fields           = [];
        $web_form_details = null;
        $parsed_fields    = null;
        $errors_found     = false;
        foreach ($_POST[ 'WebFormRowField' ] as $field_id => $f)
        {
            /** @var WebFormRowField $field */
            $field = WebFormRowField::findOne([ 'id' => $field_id ]);
            if (!isset($web_form_details))
            {
                /** @var WebFormRow $row */
                $row = $field->webFormRow;
                /** @var WebForm $form */
                $form = $row->webForm;
                /** @var WebFormDetail $web_form_details */
                $web_form_details = WebFormDetail::find()
                                                 ->where([
                                                     'web_form' => $form->id,
                                                     'language' => Yii::$app->language,
                                                 ])
                                                 ->one();
            }
            $field->text = $f[ 'text' ];
            $text        = $field->text;
            $placeholder = $field->placeholder;
            $hint        = $field->hint;

            // Create a DynamicModel and validate it.
            $defaultRules = Fields::getRules($field->type);

            // Short term hack to fix required fields in webforms TODO fixme
            $validationRules = [];
            foreach ($defaultRules as $defaultRule)
            {

                if ($defaultRule[ 1 ] === 'required')
                {
                    if ($field->required === 1)
                    {
                        $validationRules[] = $defaultRule;
                    }
                }
                else
                {
                    $validationRules[] = $defaultRule;
                }

            }

            // Create a DynamicModel and validate it.
            $field_model = DynamicModel::validateData(compact('text', 'placeholder', 'hint'), $validationRules);

            if ($field_model->hasErrors())
            {
                $parsed_fields[ $field->id ][ 'errors' ] = $field_model->errors;
                $errors_found                            = true;
            }
            $parsed_fields[ $field->id ][ 'value' ] = $field->text;

            // Validation has succeeded so we add this to the response / mail
            $fields[] = [
                'field_id'      => $field->id,
                'field_type'    => $field->type,
                'field_name'    => $field->name,
                'user_response' => $field->text,
                'placeholder'   => $field->placeholder,
                'hint'          => $field->hint,
                'is_sensitive'  => $field->is_sensitive,
            ];
        }

        // IF ERRORS HAVE BEEN FOUND, REDIRECT AND DISPLAY ERRORS.
        if ($errors_found)
        {
            Yii::$app->session->set('parsed_fields', $parsed_fields);

            // Return to the previous page.
            return Yii::$app->response->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        }

        // Send out the mails
        $mails = $this->sendSubmissionThroughMail($web_form_details, $fields);

        // Save to database.
        $this->saveSubmissionToDb($web_form_details, $mails, $fields);

        // Now redirect the user to the Thank you message page and run the JavaScript if any.
        return $this->render('submission', [
            'model' => $web_form_details,
        ]);
    }


    /**
     * Send out the mails to the specified mail addresses.
     *
     * @param WebFormDetail $web_form_details
     * @param               $fields
     *
     * @return mixed
     */
    public function sendSubmissionThroughMail($web_form_details, $fields)
    {
        // All validation has been passed and we now have to proceed.
        // Create a JSON value to save to the DB.
        $mails                                 = json_decode($web_form_details->mail);
        $response                              = [];
        $response[ 'web_form' ]                = $web_form_details->title;
        $response[ 'web_form_language' ]       = $web_form_details->language;
        $response[ 'web_form_description' ]    = $web_form_details->description;
        $response[ 'web_form_mails' ]          = $mails;
        $response[ 'web_form_submit_script' ]  = $web_form_details->script;
        $response[ 'web_form_submit_message' ] = $web_form_details->message;
        $response[ 'fields' ]                  = $fields;
        $submission                            = json_encode($response, JSON_PRETTY_PRINT);

        // Create a new entry to DB
        /** @var WebFormSubmission $entry */
        $entry             = new WebFormSubmission();
        $entry->web_form   = $web_form_details->web_form;
        $entry->language   = $web_form_details->language;
        $entry->submission = $submission;
        $entry->created_at = time();
        $entry->updated_at = time();

        $mails = $entry->decodedMails();

        // ONLY SEND IF THE WEB_FORM_DETAIL REQUIRES YOU TO SEND THE ACTUAL MAIL.
        if ($web_form_details->send_mail)
        {
            Yii::$app->mailer->compose('/mails/form-submission', [ 'model' => $entry ])
                             ->setFrom(\Yii::$app->params[ 'adminEmail' ])
                             ->setTo($mails)
                             ->setSubject($entry->getMailSubject())
                             ->send();
        }

        return $mails;
    }


    /**
     * Save non sensitive data to our database.
     *
     * @param $web_form_details
     * @param $mails
     * @param $fields
     *
     * @return bool|WebFormSubmission
     */
    private function saveSubmissionToDb($web_form_details, $mails, $fields)
    {
        // Create a new entry to DB
        /** @var WebFormSubmission $entry */
        $wfs             = new WebFormSubmission();
        $wfs->web_form   = $web_form_details->web_form;
        $wfs->language   = $web_form_details->language;
        $wfs->created_at = time();
        $wfs->updated_at = time();

        // BEFORE SAVE WE NEED TO CHECK IF THERE IS SENSITIVE DATA THAT WE NEED TO REMOVE.
        $i = 0;
        foreach ($fields as $field)
        {
            if ($field[ 'is_sensitive' ] == true)
            {
                // We are now changing the value of the user_response but we could just deleted.
                // Waiting on confirmation from the admins.
                $fields[ $i ][ 'user_response' ] = \Yii::t('app', 'Hidden Sensitive Data');
                // unset($fields[ $i ]);
            }
            $i++;
        }
        $response                              = [];
        $response[ 'web_form' ]                = $web_form_details->title;
        $response[ 'web_form_language' ]       = $web_form_details->language;
        $response[ 'web_form_description' ]    = $web_form_details->description;
        $response[ 'web_form_mails' ]          = $mails;
        $response[ 'web_form_submit_script' ]  = $web_form_details->script;
        $response[ 'web_form_submit_message' ] = $web_form_details->message;
        $response[ 'fields' ]                  = $fields;
        $submission                            = json_encode($response, JSON_PRETTY_PRINT);

        $wfs->submission = $submission;

        // Save the submission.
        return $wfs->save();
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
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'submit' => [ 'post' ]
                ]
            ]
        ];
    }

}
