<?php

namespace mobilejazz\yii2\cms\backend\modules\i18n;

use mobilejazz\yii2\cms\backend\modules\i18n\models\I18nMessage;
use mobilejazz\yii2\cms\backend\modules\i18n\models\I18nSourceMessage;
use Yii;

class Module extends \yii\base\Module
{

    public $controllerNamespace = 'mobilejazz\yii2\cms\backend\modules\i18n\controllers';

    /**
     * @param \yii\i18n\MissingTranslationEvent $event
     */
    public static function missingTranslation($event)
    {
        // Check if the source message exists.
        $message = I18nSourceMessage::find()
                                    ->where([ "message" => $event->message ])
                                    ->one();

        // Only save source messages and messages in the categories defined.
        $configFile = Yii::getAlias('@common/config/extract.php');

        $config = array_merge([
            'translator'       => 'Yii::t',
            'overwrite'        => false,
            'removeUnused'     => false,
            'markUnused'       => true,
            'sort'             => false,
            'format'           => 'php',
            'ignoreCategories' => [ ],
        ], require($configFile));

        if (!in_array($event->category, $config[ 'ignoreCategories' ]))
        {

            // The message DOES NOT exist, therefore
            // we have to create a new I18nSourceMessage.
            if (!isset($message))
            {
                $message           = new I18nSourceMessage();
                $message->category = $event->category;
                $message->message  = $event->message;
                $message->save(false);
            }

            if (!isset($message) && $message->id == null)
            {
                $message = I18nSourceMessage::find()
                                            ->where([ "message" => $event->message ])
                                            ->one();
            }

            // Now check if the translation entry has been created
            // If not present, place it into the database.
            if ($event->language !== 'en')
            {
                $translated_message = I18nMessage::find()
                                                 ->where([ 'id' => $message->id, 'language' => $event->language ])
                                                 ->one();
                if (!isset($translated_message))
                {
                    $translated_message                = new I18nMessage();
                    $translated_message->id            = $message->id;
                    $translated_message->language      = $event->language;
                    $translated_message->translation   = "";
                    $translated_message->sourceMessage = $event->message;
                    $translated_message->category      = $event->category;
                    $translated_message->save();
                }
            }
        }
    }


    public function init()
    {
        parent::init();
    }
}
