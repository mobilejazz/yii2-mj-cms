<?php
namespace mobilejazz\yii2\cms\backend\models\base;

use mobilejazz\yii2\cms\common\models\User;
use Yii;
use yii\base\Model;
use yii\db\StaleObjectException;

/**
 * Password reset request form
 */
abstract class PasswordResetRequestForm extends Model
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
                'targetClass' => User::className(),
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
                $url = \Yii::$app->urlManager->createAbsoluteUrl([ 'site/reset-password', 'token' => $user->password_reset_token ]);

                \Yii::$app->mailer->htmlLayout = "/mails/html-layout";

                $mail = Yii::$app->mailer->compose('/mails/password-reset-token', [
                    'model' => $user,
                    'url'   => $url,
                ])
                                         ->setFrom(\Yii::$app->params[ 'adminEmail' ])
                                         ->setTo($user->email)
                                         ->setSubject(\Yii::t('app', 'Password reset for {app}', [ 'app' => \Yii::$app->name, ]))
                                         ->send();

                assert($mail);

                return true;
            }
        }
        catch (StaleObjectException $e)
        {
            throw $e;
        }
    }
}