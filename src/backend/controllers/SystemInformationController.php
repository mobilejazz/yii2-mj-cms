<?php

namespace mobilejazz\yii2\cms\backend\controllers;

use mobilejazz\yii2\cms\common\models\User;
use probe\Factory;
use yii;
use yii\web\Controller;
use yii\web\Response;

class SystemInformationController extends Controller
{

    public $layout = 'common';


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

    public function actionIndex()
    {
        $provider = Factory::create();
        if ($provider)
        {
            if (Yii::$app->request->isAjax)
            {
                Yii::$app->response->format = Response::FORMAT_JSON;
                if ($key = Yii::$app->request->get('data'))
                {
                    switch ($key)
                    {
                        case 'cpu_usage':
                            return $provider->getCpuUsage();
                            break;
                        case 'memory_usage':
                            return ($provider->getTotalMem() - $provider->getFreeMem()) / $provider->getTotalMem();
                            break;
                    }
                }
            }
            else
            {
                return $this->render('index', [ 'provider' => $provider ]);
            }
        }
        else
        {
            return $this->render('fail');
        }
    }
}