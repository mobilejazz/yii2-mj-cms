<?php

namespace mobilejazz\yii2\cms\common\components;

use ReflectionClass;
use Yii;
use yii\base\Component;
use yii\base\Exception;

class SidebarConfig extends Component
{

    public $translationCategory = 'backend';

    public $menuItems;

    public function build(){

        foreach($this->menuItems as &$entry){

            // Translate label
            $label = $entry['label'];

            $entry['label'] = Yii::t($this->translationCategory, $label);
            $entry['labelOriginal'] = $label;

            // Determine if visible

            $visible = $entry['visible'];

            if(isset($visible) && is_string($visible)){

                // we assume a class name has been provided which has a method isAllowed($user)

                $reflection = new ReflectionClass($visible);
                if(!$reflection->hasMethod('isAllowed')) throw new Exception("Method isAllowed not found on instance of $visible");

                $object = $reflection->newInstance();
                $method = $reflection->getMethod('isAllowed');
                $visible = $method->invoke($object, Yii::$app->user->identity);
                $entry['visible'] = $visible;

            }
        }

        return $this->menuItems;

    }

}