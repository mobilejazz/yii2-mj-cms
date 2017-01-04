<?php

namespace mobilejazz\yii2\cms\common\models;

use mobilejazz\yii2\cms\common\components\TimeStampActiveRecord;
use yii;
use yii\db\Expression;

/**
 * This is the base-model class for table "user_notification".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string  $item_class
 * @property integer $item_id
 * @property string  $message
 * @property string  $data
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $sent_at
 * @property integer $read_at
 *
 * @property User    $user
 */
class UserNotification extends TimeStampActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_notification';
    }


    public function send()
    {
        $data = json_decode($this->data);
        \Yii::$app->pn->send2d($this->getUser()
                                    ->one()->username, $this->message, $data);
        $this->sent_at = new Expression('NOW()');
        $this->save();
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), [ 'id' => 'user_id' ]);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'user_id', 'message' ], 'required' ],
            [ [ 'user_id', 'item_id' ], 'integer' ],
            [ [ 'data' ], 'string' ],
            [ [ 'created_at', 'updated_at', 'sent_at', 'read_at' ], 'safe' ],
            [ [ 'item_class', 'message' ], 'string', 'max' => 255 ]
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('app', 'ID'),
            'user_id'    => Yii::t('app', 'User ID'),
            'item_class' => Yii::t('app', 'Item Class'),
            'item_id'    => Yii::t('app', 'Item ID'),
            'message'    => Yii::t('app', 'Message'),
            'data'       => Yii::t('app', 'Data'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'sent_at'    => Yii::t('app', 'Sent At'),
            'read_at'    => Yii::t('app', 'Read At'),
        ];
    }
}
