<?php
namespace mobilejazz\yii2\cms\common\models;

use yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{

    public $email;

    public $password;

    public $rememberMe = true;

    private $_user = false;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // email and password are both required
            [ [ 'email', 'password' ], 'required' ],
            // rememberMe must be a boolean value
            [ 'rememberMe', 'boolean' ],
            // password is validated by validatePassword()
            [ 'password', 'validatePassword' ],
        ];
    }


    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array  $params    the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if ($params)
        {
            // NOTHING.
        }
        if (!$this->hasErrors())
        {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password))
            {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }


    /**
     * Finds user by [[email]]
     *
     * @return User
     */
    public function getUser()
    {
        if ($this->_user === false)
        {
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }


    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate())
        {
            $tr = Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);

            return $tr;
        }
        else
        {
            return false;
        }
    }
}
