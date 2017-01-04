<?php

namespace mobilejazz\yii2\cms\common\models;

use mobilejazz\yii2\cms\common\components\TimeStampActiveRecord;

/**
 *
 * @property integer $id
 * @property string  $about
 * @property string  $country
 * @property string  $addressline1
 * @property string  $addressline2
 * @property string  $city
 * @property string  $postalcode
 * @property string  $phonetype
 * @property string  $phone
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User    $id0
 */
class UserProfile extends TimeStampActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_profile';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'created_at', 'updated_at' ], 'safe' ],
            [
                [
                    'about',
                ],
                'string',
                'max' => 255
            ]
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => \Yii::t('backend', 'ID'),
            'about'        => \Yii::t('backend', 'About'),
            'country'      => \Yii::t('backend', 'Country'),
            'addressline1' => \Yii::t('backend', 'Address Line 1'),
            'addressline2' => \Yii::t('backend', 'Address Line 2'),
            'city'         => \Yii::t('backend', 'City'),
            'postalcode'   => \Yii::t('backend', 'Postal Code'),
            'phonetype'    => \Yii::t('backend', 'Phone Type'),
            'phone'        => \Yii::t('backend', 'Phone'),
            'created_at'   => \Yii::t('backend', 'Created At'),
            'updated_at'   => \Yii::t('backend', 'Updated At'),
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId0()
    {
        return $this->hasOne(User::className(), [ 'id' => 'id' ]);
    }
}
