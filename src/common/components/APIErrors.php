<?php

namespace mobilejazz\yii2\cms\common\components;

use yii;
use yii\web\ConflictHttpException;
use yii\web\NotFoundHttpException;

class APIErrors
{

    const ERROR_DUPLICATED_EMAIL = 101;
    const ERROR_FORBIDDEN = 102;
    const ERROR_USER_NOT_FOUND = 103;
    const ERROR_UNKNOWN = 500;


    public static function err($err)
    {
        switch ($err)
        {
            case APIErrors::ERROR_USER_NOT_FOUND:
                throw new NotFoundHttpException(Yii::t('api', 'User not found'), APIErrors::ERROR_USER_NOT_FOUND);
            case APIErrors::ERROR_FORBIDDEN:
                throw new yii\web\ForbiddenHttpException(Yii::t('api', 'You are not allowed to access this page'), APIErrors::ERROR_FORBIDDEN);
            case APIErrors::ERROR_DUPLICATED_EMAIL:
                throw new ConflictHttpException(Yii::t('api', 'Email already in use by another account'), APIErrors::ERROR_DUPLICATED_EMAIL);
            case APIErrors::ERROR_UNKNOWN:
                throw new yii\web\ServerErrorHttpException(Yii::t('api', 'Unknown Error. Try again later.'), APIErrors::ERROR_UNKNOWN);

        }
    }
}