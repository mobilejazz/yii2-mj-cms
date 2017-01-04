<?php

namespace mobilejazz\yii2\cms\common\models;

use mobilejazz\yii2\cms\frontend\widgets\ActiveForm as ActiveFormFE;
use yii;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;

class Fields
{

    const FIELD_SUBTITLE = 'subtitle';
    const FIELD_TITLE = 'title';
    const FIELD_EMAIL = 'email';
    const FIELD_TITLE_BOLD = 'title-bold';
    const FIELD_TITLE_NON_BOLD = 'title-non-bold';
    const FIELD_TEXT_BOX = 'text-box';
    const FIELD_PASSWORD = 'password';
    const FIELD_TEXT_AREA = 'text-area';
    const FIELD_CMS_COLOR_PALETTE_BACKGROUND = 'cms-color-palette';
    const FIELD_CMS_COLOR_PALETTE_TEXT = 'cms-text-color-palette';
    const FIELD_IMAGE = 'image';
    const FIELD_LINK_NAME = 'link-name';
    const FIELD_LINK_URL = 'link-url';
    const FIELD_LINK_COLOR = 'link-color';
    const FIELD_LINK_TARGET = 'link-target';
    const FIELD_HEX_COLOR = 'hex-color';
    const FIELD_BG_COLOR = 'bg-color';
    const FIELD_FRAME_COLOR = 'frame-color';
    const FIELD_CSS_CLASS = 'css-class';
    const FIELD_LTR_SWITCH_INPUT = 'ltr-switch-input';
    const FIELD_IS_OPEN = 'is-open';
    const FIELD_BOLD_SELECTOR = 'bold-selector';
    const FIELD_BUTTON_TEXT = 'button-text';
    const FIELD_ICON = 'icon';
    const FIELD_FILE = 'file';
    const FIELD_HTML_CODE = 'html-code';
    const FIELD_CHECKBOX = 'checkbox';
    const FIELD_BUTTON_ALIGNMENT = 'button-alignment';


    /**
     * Returns a map of fields <key><name>
     * @return array[] of views
     */
    public static function asMap()
    {
        $array_to_return = [];

        $views = self::getFields();
        foreach ($views as $key => $view)
        {
            $array_to_return[ $key ] = $view[ 'name' ];
        }

        return $array_to_return;
    }


    /**
     * Gets all fields defined in the configuration file.
     * @return array configuration
     */
    public static function getFields()
    {
        static $config = null;
        if ($config == null)
        {
            /** @noinspection PhpIncludeInspection */
            /** @noinspection PhpUnusedParameterInspection */

            $params = \Yii::$app->params[ 'cms' ];
            if (isset($params))
            {
                $params = $params[ 'fields' ];
            }

            $config = require(\Yii::getAlias('@mobilejazz/yii2/cms/common/config/content/fields.php'));

            foreach ($params as $file)
            {
                $contents = require(\Yii::getAlias($file));
                $config   = ArrayHelper::merge($config, $contents);
            }

            $config = array_merge([
                'form-dropdown' => [
                    'name'   => Yii::t('backend', 'Form Dropdown'),
                    'widget' => function ($form, $field, $label, $placeholder, $hint)
                    {
                        return self::field($form, $field, "[$field->id]text")
                                   ->dropDownList(WebForm::asMap())
                                   ->label(Yii::t('backend', 'Choose one of your existing Forms.'));
                    },
                ],
            ], $config);
        }

        return $config;
    }


    /**
     *
     * @param ActiveForm     $form
     * @param                $model
     * @param                $attribute
     * @param array          $options
     *
     * @return object
     * @throws \yii\base\InvalidConfigException
     */
    public static function field($form = null, $model, $attribute, $options = [])
    {
        if (isset($form) && $form != null)
        {
            return $form->field($model, $attribute, $options);
        }
        else
        {
            $config = [];
            if ($config instanceof \Closure)
            {
                $config = call_user_func($config, $model, $attribute);
            }
            if (!isset($config[ 'class' ]))
            {
                $config[ 'class' ] = 'mobilejazz\yii2\cms\common\widgets\ActiveField';
            }

            return Yii::createObject(ArrayHelper::merge($config, $options, [
                'model'     => $model,
                'attribute' => $attribute,
            ]));

        }
    }


    /**
     * Gets the rules associated with a given field.
     * This allows us to validate
     *
     * @param      $id
     *
     * @param null $component_id
     *
     * @return array the rules associated with this field.
     * @throws BadRequestHttpException
     *
     */
    public static function getRules($id, $component_id = null)
    {
        if ($component_id != null)
        {
            $component = Components::getComponent($component_id);
            if (isset($component[ 'fields' ][ $id ][ 'required' ]))
            {
                $req = $component[ 'fields' ][ $id ][ 'required' ];
                if (!$req)
                {
                    return null;
                }
            }
        }
        if (isset(self::getField($id)[ 'rules' ]))
        {
            return self::getField($id)[ 'rules' ];
        }

        return null;
    }


    /**
     * Gets a field given an ID.
     *
     * @param $id
     *
     * @return mixed
     * @throws BadRequestHttpException
     */
    public static function getField($id)
    {
        foreach (self::getFields() as $key => $field)
        {
            if ($key == $id)
            {
                return $field;
            }
        }
        throw new BadRequestHttpException(Yii::t('backend', "The field with the id: $id does not exist."));
    }


    /**
     * Returns the widget that can then be rendered.
     *
     * @param ActiveForm|ActiveFormFE        $form
     * @param ComponentField|WebFormRowField $field
     * @param string                         $label
     * @param string                         $placeholder
     * @param string                         $hint
     * @param array                          $options
     *
     * @return mixed the function result, or false on error
     * @throws BadRequestHttpException
     */
    public static function getWidget($form = null, $field, $label, $placeholder, $hint, $options = null)
    {
        $type = $field->type;
        if (isset(self::getField($type)[ 'widget' ]))
        {
            return call_user_func(self::getField($type)[ 'widget' ], $form, $field, $label, $placeholder, $hint, $options);
        }

        return null;
    }


    /**
     * Gets a name for a given field.
     *
     * @param $component_type
     * @param $field_type
     *
     * @return string the name of the field. Null if not found.
     * @throws BadRequestHttpException
     */
    public static function getName($component_type, $field_type)
    {
        if (isset($component_type) && $component_type != null)
        {
            $name = Components::getComponent($component_type)[ 'fields' ][ $field_type ][ 'name' ];
            if (isset($name))
            {
                return $name;
            }
        }
        if (isset(self::getField($field_type)[ 'name' ]))
        {
            return self::getField($field_type)[ 'name' ];
        }

        return null;
    }


    /**
     * Sets a default value for a field if required.
     *
     * @param $component_type
     * @param $field_type
     *
     * @return null
     * @throws BadRequestHttpException
     */
    public static function getDefaultValue($component_type, $field_type)
    {
        if (isset($component_type) && $component_type != null)
        {
            $name = Components::getComponent($component_type)[ 'fields' ][ $field_type ][ 'default' ];
            if (isset($name))
            {
                return $name;
            }
        }
        if (isset(self::getField($field_type)[ 'default' ]))
        {
            return self::getField($field_type)[ 'default' ];
        }

        return null;
    }
}