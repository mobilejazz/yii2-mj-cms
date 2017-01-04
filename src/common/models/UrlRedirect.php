<?php

namespace mobilejazz\yii2\cms\common\models;

use mobilejazz\yii2\cms\common\components\TimeStampActiveRecord;
use yii;

/**
 *
 * @property integer $id
 * @property string  $origin_slug
 * @property string  $destination_slug
 * @property integer $created_at
 * @property integer $updated_at
 */
class UrlRedirect extends TimeStampActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'url_redirect';
    }


    /**
     * We need to avoid Redirect Loops.
     *
     * Therefore we need to check somethings:
     * 1- Check if the origin and the destination are equal.
     * 2- Check if the origin exists as an origin. SHOULD NOT BE ALLOWED.
     * 3- Check if the destination exists either as an origin. SHOULD NOT BE ALLOWED.
     *
     * @param bool $insert
     *
     * @return boolean whether the insertion or updating should continue.
     * If false, the insertion or updating will be cancelled.
     */
    public function beforeSave($insert)
    {
        $can_save = true;

        if (substr($this->origin_slug, 0, 1) != "/")
        {
            $can_save = false;
            \Yii::$app->getSession()
                      ->setFlash('url_redirect_error', 'The origin has to start with a / symbol. Please double check that.');

            return $can_save;
        }  // Check that the destination and origin are not equal.
        elseif (strcmp($this->origin_slug, $this->destination_slug) == 0)
        {
            $can_save = false;
            \Yii::$app->getSession()
                      ->setFlash('url_redirect_error', 'The origin and the destination can not be equal. Please double check that.');

            return $can_save;
        }

        // Now check with the other slugs.
        /** @var UrlRedirect $redirects */
        $redirects = UrlRedirect::find()
                                ->orderBy([ 'updated_at' => SORT_DESC ])
                                ->all();

        foreach ($redirects as $r)
        {
            // If we are checking this same instance, continue.
            if ($this->id == $r->id)
            {
                continue;
            } // If the origin slug is already somewhere else as an origin slug, this can not be saved.
            elseif (strcmp($r->origin_slug, $this->origin_slug) == 0)
            {
                \Yii::$app->getSession()
                          ->setFlash('url_redirect_error',
                              'The origin slug is already used somewhere else as an origin slug. Please double check that.');
                $can_save = false;
                break;
            } // If the destination slug is already used somewhere else as an origin slug, this can not be saved.
            elseif (strcmp($this->destination_slug, $r->origin_slug) == 0)
            {
                \Yii::$app->getSession()
                          ->setFlash('url_redirect_error',
                              'The destination slug is already used somewhere else as an origin slug. Please double check that.');
                $can_save = false;
                break;
            }
        }

        // Update timings.
        if ($can_save)
        {
            if ($this->isNewRecord)
            {
                $this->created_at = time();
                $this->updated_at = time();
            }
            else
            {
                $this->updated_at = time();
            }
        }

        return $can_save;
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'origin_slug', 'destination_slug' ], 'required' ],
            [ [ 'created_at', 'updated_at' ], 'integer' ],
            [ [ 'origin_slug', 'destination_slug' ], 'string', 'max' => 255 ]
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'               => Yii::t('app', 'ID'),
            'origin_slug'      => Yii::t('app', 'Origin Slug'),
            'destination_slug' => Yii::t('app', 'Destination Slug'),
            'created_at'       => Yii::t('app', 'Created At'),
            'updated_at'       => Yii::t('app', 'Updated At'),
        ];
    }

}
