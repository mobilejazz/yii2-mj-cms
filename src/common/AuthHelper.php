<?php

namespace mobilejazz\yii2\cms\common;

use Yii;

class AuthHelper {

    /**
     * @param \Closure $beforeRedirect
     * @param String $redirectUrl
     * @return \Closure
     */
    static function denyCallback($beforeRedirect = null, $redirectUrl = null){

        return function(){
            
            $user = Yii::$app->getUser();

            if($user->getIsGuest()){
                $user->loginRequired();
            } else {

                if(isset($beforeRedirect)) $beforeRedirect();

                if(!isset($redirectUrl)) $redirectUrl = Yii::$app->request->referrer ?: Yii::$app->homeUrl;
                Yii::$app->response->redirect($redirectUrl);

            }    
        };
        
    }


}