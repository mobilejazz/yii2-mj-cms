<?php

namespace mobilejazz\yii2\cms\common\widgets;

use yii;
use yii\bootstrap\Html;
use yii\bootstrap\Widget;

/**
 * Panel renders a bootsrap panel.
 *
 * The following example will show the content enclosed between the [[begin()]]
 * and [[end()]] calls within the panel:
 *
 * ~~~php
 * BootstrapPanel::begin([
 *     'title' => 'Hello World',
 * ]);
 *
 * echo 'Say hello...';
 *
 * Panel::end();
 * ~~~
 */
class BootstrapPanel extends Widget
{

    public $title;

    public $target;

    public $open = true;

    public $class;


    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();

        $this->target = Yii::$app->security->generateRandomString(3);

        $this->initOptions();

        $caret = $this->open ? 'up' : 'down';

        echo Html::beginTag('div', $this->options) . "\n";
        echo Html::beginTag('div', [ 'class' => 'panel-heading with-border ' ]) . "\n";
        echo Html::beginTag('i', [
                'class'         => 'fa fa-caret-' . $caret . ' pull-right',
                'style'         => 'cursor: pointer;',
                'data-toggle'   => 'collapse',
                'data-target'   => "#" . $this->target,
                'aria-expanded' => true,
                'aria-controls' => $this->target,
            ]) . "\n";
        echo Html::endTag("i");
        echo $this->renderHeader() . "\n";
        echo Html::endTag('div');
        echo $this->renderBodyBegin() . "\n";
    }


    /**
     * Initializes the widget options.
     * This method sets the default values for various options.
     */
    protected function initOptions()
    {
        $this->options = array_merge([
            'class' => 'panel panel-default',
        ], $this->options);
    }


    /**
     * Renders the header HTML markup of the panel
     * @return string the rendering result
     */
    protected function renderHeader()
    {
        if ($this->title !== null)
        {
            $s = "<h3 class='panel-title'>" . $this->title . "</h3>";

            return Html::tag('div', "\n" . $s . "\n");
        }
        else
        {
            return null;
        }
    }


    /**
     * Renders the opening tag of the panel body.
     * @return string the rendering result
     */
    protected function renderBodyBegin()
    {
        $class = 'panel-body collapse';
        if ($this->open)
        {
            $class .= ' in';
        }

        return Html::beginTag('div', [
            'id'            => $this->target,
            'class'         => $class,
            'aria-expanded' => $this->open,
        ]);
    }


    /**
     * Renders the widget.
     */
    public function run()
    {
        echo "\n" . $this->renderBodyEnd();
        echo "\n" . Html::endTag('div'); // panel-content
    }


    /**
     * Renders the closing tag of the panel body.
     * @return string the rendering result
     */
    protected function renderBodyEnd()
    {
        return Html::endTag('div');
    }
}
