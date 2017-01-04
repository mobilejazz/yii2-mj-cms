<?php
namespace mobilejazz\yii2\cms\frontend\models;

use mobilejazz\yii2\cms\common\models\User;
use yii;
use yii\base\Model;
use yii\db\StaleObjectException;
use yii\helpers\Url;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{

    public $email;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ 'email', 'filter', 'filter' => 'trim' ],
            [ 'email', 'required' ],
            [ 'email', 'email' ],
            [
                'email',
                'exist',
                'targetClass' => '\common\models\User',
                'message'     => \Yii::t('app', 'There is no user with such email')
            ],
        ];
    }


    /**
     * Sends an email with a link, for resetting the password.
     * @return bool whether the email was send
     * @throws StaleObjectException
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'email' => $this->email,
        ]);

        if (!$user)
        {
            return false;
        }

        try
        {
            if (!User::isPasswordResetTokenValid($user->password_reset_token))
            {
                $user->generatePasswordResetToken();
            }

            if (!$user->save())
            {
                return false;
            }

            else
            {
                $url = \Yii::$app->urlManager->createAbsoluteUrl([ Url::to([ '/reset-password' ]), 'token' => $user->password_reset_token ]);

                \Yii::$app->mailer->htmlLayout = "/mails/html-layout";

                Yii::$app->mailer->compose('/mails/password-reset-token', [
                    'model' => $user,
                    'url'   => $url,
                ])
                                 ->setFrom(\Yii::$app->params[ 'adminEmail' ])
                                 ->setTo($user->email)
                                 ->setSubject(\Yii::t('app', 'Password reset for {app}', [ 'app' => \Yii::$app->name, ]))
                                 ->send();

                return true;
            }
        }
        catch (StaleObjectException $e)
        {
            throw $e;
        }
    }
}