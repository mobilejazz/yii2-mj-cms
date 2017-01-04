<?php

namespace mobilejazz\yii2\cms\backend\components;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

abstract class AdminController extends Controller
{

    public $userClass = 'mobilejazz\yii2\cms\common\models\User';

    public function behaviors()
    {
        return [
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => [ 'post' ],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only'  => [ 'index', 'view', 'create', 'update', 'delete' ],
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => [ 'index', 'view', 'create', 'update', 'delete' ],
                        'roles'   => [ 'admin' ],
                    ],
                ],
            ],
        ];
    }


    public function checkOwner($rule, $action)
    {
        $model = $this->findModel($_GET[ "id" ]);
        if (isset($model->user_id) && $model->user_id == \Yii::$app->user->id)
        {
            return true;
        }

        if (get_class($model) == $this->userClass && $model->id == \Yii::$app->user->id)
        {
            return true;
        }

        return false;
    }
}