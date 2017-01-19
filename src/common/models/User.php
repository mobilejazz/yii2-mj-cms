<?php
namespace mobilejazz\yii2\cms\common\models;

use mobilejazz\yii2\cms\common\components\TimeStampActiveRecord;
use mobilejazz\yii2\oauth2server\models\OauthAccessTokens;
use OAuth2\Storage\UserCredentialsInterface;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer            $id
 * @property string             $email
 * @property string             $auth_key
 * @property string             $password_hash
 * @property string             $password_reset_token
 * @property string             $name
 * @property string             $last_name
 * @property integer            $role
 * @property string             $picture
 * @property integer            $status
 * @property integer            $created_at
 * @property integer            $updated_at
 *
 * @property UserNotification[] $userNotifications
 * @property UserProfile        $userProfile
 * @property string             $password password
 */
class User extends TimeStampActiveRecord implements IdentityInterface, UserCredentialsInterface
{

    const SCENARIO_LOGIN = 'login';
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    const ROLE_USER = 10;
    const ROLE_TRANSLATOR = 13;
    const ROLE_EDITOR = 15;
    const ROLE_ADMIN = 20;

    const STATUS_DELETED = 0;
    const STATUS_AWAITING_VALIDATION = 5;
    const STATUS_INVALIDATED = 15;
    const STATUS_ACTIVE = 10;

    public $password;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }


    /**
     * Return the key and the name of all the views as a map.
     * @return array[] of views
     */
    public static function rolesAsMap()
    {
        $array_to_return = [];

        $roles = self::getRoles();
        foreach ($roles as $key => $view)
        {
            $array_to_return[ $key ] = $view;
        }

        return $array_to_return;
    }


    /**
     * @param bool $role
     *
     * @return array|mixed
     */
    public static function getRoles($role = false)
    {

        $roles = self::roles();

        return $role !== false ? ArrayHelper::getValue($roles, $role) : $roles;
    }


    /**
     * @return array List of roles
     */
    public static function roles()
    {
        return [
            self::ROLE_USER       => Yii::t('backend', "User"),
            self::ROLE_ADMIN      => Yii::t('backend', "Admin"),
            self::ROLE_EDITOR     => Yii::t('backend', "Editor"),
            self::ROLE_TRANSLATOR => Yii::t('backend', 'Translator'),
        ];
    }


    /**
     * Return the key and the name of all the views as a map.
     * @return array[] of views
     */
    public static function statusAsMap()
    {
        $array_to_return = [];

        $status = self::status();
        foreach ($status as $key => $stat)
        {
            $array_to_return[ $key ] = $stat;
        }

        return $array_to_return;
    }


    public static function status()
    {
        return [
            self::STATUS_DELETED             => \Yii::t('app', "Deleted"),
            self::STATUS_AWAITING_VALIDATION => \Yii::t('app', 'Awaiting Validation'),
            self::STATUS_ACTIVE              => \Yii::t('app', "Active"),
            self::STATUS_INVALIDATED         => \Yii::t('app', 'Invalidated')
        ];
    }


    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     *
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token))
        {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status'               => [
                self::STATUS_ACTIVE,
                self::STATUS_AWAITING_VALIDATION,
                self::STATUS_INVALIDATED,
            ]
        ]);
    }


    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     *
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token))
        {
            return false;
        }
        $expire    = Yii::$app->params[ 'user.passwordResetTokenExpire' ];
        $parts     = explode('_', $token);
        $timestamp = (int) end($parts);

        return $timestamp + $expire >= time();
    }


    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne([
            'id'     => $id,
            'status' => [
                self::STATUS_ACTIVE,
                self::STATUS_AWAITING_VALIDATION,
                self::STATUS_INVALIDATED,
            ]
        ]);
    }


    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        /** @var OauthAccessTokens $user $user */
        $user = OauthAccessTokens::find()
                                 ->where([ "access_token" => $token ])
                                 ->one();
        if ($user)
        {
            //If we have client_credentials enabled, we need to return a fake user to allow access
            if (isset($user->client_id) && $user->user_id == null)
            {
                return new User();
            }

            return static::findOne([ "id" => $user->user_id ]);
        }

        return null;
    }


    /**
     * @param null $roles
     *
     * @return array map to be used in DropDowns or Lists
     */
    public static function getDataList($roles = null)
    {
        if (count($roles) > 0)
        {
            $query = User::find();
            foreach ($roles as $i => $role)
            {
                if ($i == 0)
                {
                    $query->where([ 'role' => $role ]);
                }
                else
                {
                    $query->orWhere([ 'role' => $role ]);
                }
            }
        }
        else
        {
            $query = User::find();
        }

        $models = $query->orderBy('email')
                        ->asArray()
                        ->all();

        return ArrayHelper::map($models, 'id', 'email');
    }


    public function getFullName()
    {
        return $this->name . " " . $this->last_name;
    }


    /**
     * @return string
     */
    public function getRole()
    {
        switch ($this->role)
        {
            case self::ROLE_USER:
                return Yii::t('backend', "User");
                break;
            case self::ROLE_ADMIN:
                return Yii::t('backend', "Admin");
                break;
            case self::ROLE_EDITOR:
                return Yii::t('backend', 'Editor');
                break;
            case self::ROLE_TRANSLATOR:
                return Yii::t('backend', 'Translator');
        }

        return null;
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ 'status', 'default', 'value' => self::STATUS_ACTIVE ],
            [ 'status', 'in', 'range' => [ self::STATUS_ACTIVE, self::STATUS_DELETED ] ],

            [ 'email', 'filter', 'filter' => 'trim' ],
            [ 'email', 'email' ],
            [
                'email',
                'unique',
                'targetClass' => '\mobilejazz\yii2\cms\common\models\User',
                'message'     => Yii::t('app', 'This email address has already been taken.'),
            ],

            [ 'password', 'string', 'min' => 6 ],

            [ [ 'email', 'name', 'password' ], 'required', 'on' => self::SCENARIO_CREATE ],
            [ [ 'email', 'password' ], 'required', 'on' => self::SCENARIO_LOGIN ],

            [ [ 'email', 'name', 'last_name', 'role', 'status', 'password', 'picture' ], 'safe' ],

        ];
    }


    public function delete($delete_from_db = false)
    {
        $this->setStatus(self::STATUS_DELETED);

        if ($delete_from_db)
        {
            parent::delete();
        }
    }


    public function setStatus($status)
    {
        $this->status = $status;
        $this->save();
    }


    public function setRole($role)
    {
        $this->role = $role;
        $this->save();
    }


    public function gtFullName()
    {
        return ucwords($this->name . ' ' . $this->last_name);
    }


    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $auth = Yii::$app->authManager;

        /**
         * Make sure that the auth system is aware of the
         * changes that have happened here.
         */
        switch ($this->role)
        {
            case self::ROLE_ADMIN:
                $role = $auth->getRole('admin');
                if (!$role)
                {
                    $role = $auth->createRole('admin');
                    $auth->add($role);
                }
                $auth->revokeAll($this->id);
                $auth->assign($role, $this->id);
                break;
            case self::ROLE_TRANSLATOR:
                $role = $auth->getRole('translator');
                if (!$role)
                {
                    $role = $auth->createRole('translator');
                    $auth->add($role);
                }
                $auth->revokeAll($this->id);
                $auth->assign($role, $this->id);
                break;
            case self::ROLE_EDITOR:
                $role = $auth->getRole('editor');
                if (!$role)
                {
                    $role = $auth->createRole('editor');
                    $auth->add($role);
                }
                $auth->revokeAll($this->id);
                $auth->assign($role, $this->id);
                break;
            case self::ROLE_USER:
                $role = $auth->getRole('user');
                if (!$role)
                {
                    $role = $auth->createRole('user');
                    $auth->add($role);
                }
                $auth->revokeAll($this->id);
                $auth->assign($role, $this->id);
                break;
        }

        //Check if we have a UserProfile, we create one if it doesn't exists
        $profile = UserProfile::findOne($this->id);
        if (!isset($profile))
        {
            $profile     = new UserProfile();
            $profile->id = $this->id;
            $profile->save();
        }
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert))
        {
            if ($this->isNewRecord)
            {
                $this->password_hash = Yii::$app->getSecurity()
                                                ->generatePasswordHash($this->password);
                $this->generateAuthKey();
            }
            else if ($this->password)
            {
                $this->password_hash = Yii::$app->getSecurity()
                                                ->generatePasswordHash($this->password);
            }

            return true;
        }
        else
        {
            return false;
        }
    }


    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }


    /**
     * Required by OAuth2\Storage\UserCredentialsInterfaces
     *
     * @param mixed   $username
     * @param mixed   $password
     * @param boolean $updating_password
     *
     * @return bool whether credentials are valid
     */
    public function checkUserCredentials($username, $password, $updating_password = false)
    {
        $user = $this->findByEmail($username);
        if (is_null($user))
        {
            return false;
        }

        $crypted = $user->password_hash;

        if (!Yii::$app->getSecurity()
                      ->validatePassword($password, $crypted)
        )
        {
            return false;
        }
        else
        {
            return true;
        }
    }


    /**
     * Finds user by email
     *
     * @param string $email
     *
     * @return User|null
     */
    public static function findByEmail($email)
    {
        return static::findOne([
            'email'  => $email,
            'status' => [
                self::STATUS_ACTIVE,
                self::STATUS_AWAITING_VALIDATION,
                self::STATUS_INVALIDATED,
            ]
        ]);
    }


    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }


    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getStatus()
    {
        switch ($this->status)
        {
            case self::STATUS_DELETED:
                return Yii::t('backend', "Deleted");
                break;
            case self::STATUS_AWAITING_VALIDATION:
                return \Yii::t('backend', 'Awaiting Validation');
                break;
            case self::STATUS_INVALIDATED:
                return \Yii::t('backend', 'Invalidated');
                break;
            case self::STATUS_ACTIVE:
                return Yii::t('backend', "Active");
                break;
        }

        return null;
    }


    /**
     * Required by OAuth2\Storage\UserCredentialsInterfaces
     *
     * @param string $username
     *
     * @return array with keys scope and user_id
     */
    public function getUserDetails($username)
    {
        $user = $this->findByEmail($username);

        return [ 'scope' => '', 'user_id' => $user->id ];
    }


    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }


    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }


    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }


    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }


    /**
     * Validates password
     *
     * @param string $password password to validate
     *
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        if ($this->password_hash == "#")
        {
            return false;
        }

        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                   => Yii::t('app', 'ID'),
            'email'                => Yii::t('app', 'Email'),
            'auth_key'             => Yii::t('app', 'Auth Key'),
            'password_hash'        => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'name'                 => Yii::t('app', 'Name'),
            'last_name'            => Yii::t('app', 'Last Name'),
            'role'                 => Yii::t('app', 'Role'),
            'picture'              => Yii::t('app', 'Picture'),
            'status'               => Yii::t('app', 'Status'),
            'created_at'           => Yii::t('app', 'Created At'),
            'updated_at'           => Yii::t('app', 'Updated At'),
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserNotifications()
    {
        return $this->hasMany(UserNotification::className(), [ 'user_id' => 'id' ]);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfile()
    {
        return $this->hasOne(UserProfile::className(), [ 'id' => 'id' ]);
    }


    public function scenarios()
    {
        $scenarios                          = parent::scenarios();
        $scenarios[ self::SCENARIO_LOGIN ]  = [ 'email', 'password' ];
        $scenarios[ self::SCENARIO_CREATE ] = [ 'email', 'password', 'name', 'last_name', 'picture' ];
        $scenarios[ self::SCENARIO_UPDATE ] = $scenarios[ 'default' ];

        return $scenarios;
    }


    public function fields()
    {
        $fields = parent::fields();
        unset($fields[ 'password_hash' ], $fields[ 'password_reset_token' ], $fields[ 'auth_key' ], $fields[ 'status' ]);

        return $fields;
    }
}
