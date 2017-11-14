<?php

namespace mobilejazz\yii2\cms\common\components;

use yii\base\Component;
use mobilejazz\yii2\cms\common\models\User;

class PreviewService extends Component
{
    // Salt must be explicitly defined in config files
    public $salt;
    public $url_param = '_preview';

    public function getToken() {

        if($this->salt){
            $user = \Yii::$app->user->getIdentity();
            // If this is not an admin do not generate token
            if ($user !== null && !\Yii::$app->user->isGuest && $user->role !== User::ROLE_USER){

                return $this->generateToken();
            }
        }
        return '';
    }

    protected function generateToken() {
        return hash_hmac('sha256',date("Ymd"),$this->salt);
    }

    public function validateToken() {
        $token = \Yii::$app->request->getQueryParam($this->url_param,false);
        return $token === $this->generateToken();
    }


}