<?php

namespace mobilejazz\yii2\cms\backend\controllers;

use mobilejazz\yii2\cms\common\models\WebFormSubmission;
use dmstr\bootstrap\Tabs;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;

/**
 * WebFormSubmissionController implements the CRUD actions for WebFormSubmission model.
 */
class WebFormSubmissionController extends Controller
{

    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     */
    public $enableCsrfValidation = false;


    /**
     * Displays a single WebFormSubmission model.
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


    /**
     * Finds the WebFormSubmission model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return WebFormSubmission the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WebFormSubmission::findOne($id)) !== null)
        {
            return $model;
        }
        else
        {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }


    /**
     * Deletes an existing WebFormSubmission model.
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
            $model = $this->findModel($id);
            $wf    = $model->web_form;
            $model->delete();

            return $this->redirect(Url::to([
                '/web-form/',
                'id' => $wf,
            ]));
        }
        catch (\Exception $e)
        {
            $msg = (isset($e->errorInfo[ 2 ])) ? $e->errorInfo[ 2 ] : $e->getMessage();
            \Yii::$app->getSession()
                      ->addFlash('error', $msg);

            return $this->redirect(Url::previous());
        }
    }
}
