<?php

use mobilejazz\yii2\cms\common\models\ContentComponent;
use mobilejazz\yii2\cms\common\models\ContentSlug;

/**
 * @var yii\web\View       $this
 * @var string             $lang
 * @var ContentSlug        $slug
 * @var ContentComponent[] $components
 */

echo "<ul>";
/** @var ContentComponent[] $group */
foreach ($components as $group)
{
    $type = $group[ 0 ]->type;

    echo "<li><h4>" . Yii::t('backend', 'Component: ') . " " . $group[ 0 ]->type . " - " . $group[ 0 ]->title . "</h4>";

    $fields = $group[ 0 ]->componentFields;

    echo "<ul>";

    if (count($fields) > 0)
    {
        foreach ($fields as $field)
        {
            $length = strlen($field->text);
            echo "<li>" . Yii::t('backend', 'Field') . ": ";
            if ($length > 0)
            {
                echo $field->type . ": " . $field->text;
            }
            else
            {
                echo $field->type . ": " . Yii::t('backend', 'Empty value');
            }
            echo "</li>";
        }
    }

    if (isset($group[ 'children' ]))
    {
        /** @var ContentComponent[] $children */
        $children = $group[ 'children' ];
        if (count($children) > 0)
        {
            /** @var ContentComponent $child */
            foreach ($group[ 'children' ] as $child)
            {
                echo "<li>" . Yii::t('backend', 'Component') . ": " . $child->type . ": " . $child->title;

                $fields = $child->componentFields;
                if (count($fields) > 0)
                {
                    echo "<ul>";
                    foreach ($fields as $field)
                    {
                        $length = strlen($field->text);
                        echo "<li>" . Yii::t('backend', 'Field') . ": ";
                        if ($length > 0)
                        {
                            echo $field->type . ": " . $field->text;
                        }
                        else
                        {
                            echo $field->type . ": " . Yii::t('backend', 'Empty value');
                        }
                        echo "</li>";
                    }

                    echo "</ul>";
                }

                echo "</li>";
            }
        }
    }
    echo "</ul>";
    echo "</li>";
}
echo "</ul>";