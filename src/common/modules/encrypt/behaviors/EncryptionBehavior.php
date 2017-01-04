<?php
/**
 * Contains the behavior class used to encrypt data before storing it on a
 * database with an ActiveRecord class.
 */

namespace mobilejazz\yii2\cms\common\modules\encrypt\behaviors;

use mobilejazz\yii2\cms\common\Modules\encrypt\components\Encrypter;
use yii\base\Behavior;
use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

/**
 * This Behavior is used to encrypt data before storing it on the database
 * and to decrypt it upon retrieval.
 *
 * To attach this behavior to an ActiveRecord add the following code
 * ```php
 *
 * public function behaviors()
 *  {
 *      return [
 *          'encryption' => [
 *              'class' => 'common\modules\encrypt\behaviors\EncryptionBehavior',
 *              'attributes' => [
 *                  'attribute1',
 *                  'attribute2',
 *              ],
 *          ],
 *      ];
 *  }
 * ```
 */
class EncryptionBehavior extends Behavior
{

    public $attributes = [ ];


    /**
     * Adds to the behavior the listeners for the following events:
     * AFTER_FIND
     * BEFORE_INSERT
     * BEFORE_UPDATE
     * AFTER_INSERT
     * AFTER_UPDATE
     *
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND    => 'decryptAllAttributes',
            ActiveRecord::EVENT_BEFORE_INSERT => 'encryptAllAttributes',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'encryptAllAttributes',
            ActiveRecord::EVENT_AFTER_INSERT  => 'decryptAllAttributes',
            ActiveRecord::EVENT_AFTER_UPDATE  => 'decryptAllAttributes',
        ];
    }


    /**
     * Decrypts all the listed attributes by the ActiveRecord in the behavior
     * configuration.
     *
     * @param Event $event
     */
    public function decryptAllAttributes(Event $event)
    {
        foreach ($this->attributes as $attribute)
        {
            $this->decryptValue($attribute);
        }
    }


    /**
     * Decrypts the value of the given attribute.
     *
     * @param string $attribute the attribute name
     */
    private function decryptValue($attribute)
    {
        $this->owner->$attribute = $this->getEncrypter()
                                        ->decrypt($this->owner->$attribute);
    }


    /**
     * Returns the Encrypter component used by the behavior.
     *
     * @return Encrypter
     * @throws InvalidConfigException
     */
    private function getEncrypter()
    {
        try
        {
            return \Yii::$app->get('encrypter');
        }
        catch (\Exception $exc)
        {
            throw new InvalidConfigException('Encrypter component not enabled.');
        }
    }


    /**
     * Encrypts all the listed attributes by the ActiveRecord in the behavior
     * configuration.
     *
     * @param Event $event
     */
    public function encryptAllAttributes(Event $event)
    {
        foreach ($this->attributes as $attribute)
        {
            $this->encryptValue($attribute);
        }
    }


    /**
     * Encrypts the value of the given attribute.
     *
     * @param string $attribute the attribute name
     */
    private function encryptValue($attribute)
    {
        $value                   = $this->owner->$attribute;
        $this->owner->$attribute = $this->getEncrypter()
                                        ->encrypt($value);
    }

}