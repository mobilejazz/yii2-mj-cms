<?php

namespace mobilejazz\yii2\cms\common\widgets;

use yii;
use yii\bootstrap\Html;
use yii\bootstrap\Widget;

/**
 * Panel renders a bootstrap panel.
 *
 * The following example will show the content enclosed between the [[begin()]]
 * and [[end()]] calls within the panel:
 *
 * ~~~php
 * Panel::begin([
 *     'title' => 'Hello World',
 * ]);
 *
 * echo 'Say hello...';
 *
 * Panel::end();
 * ~~~
 */
class BoxPanel extends Widget
{

    public $display_header = true;

    public $title;

    public $target;

    public $open = true;

    public $type = 'default';

    public $class;

    public $sortable = false;

    public $hidden = false;

    public $collapsible = true;


    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();

        $this->target = Yii::$app->security->generateRandomString(3);

        $this->initOptions();

        $caret = $this->open ? 'angle-up' : 'angle-down';

        echo Html::beginTag('div', $this->options) . "\n";

        if ($this->display_header)
        {
            echo Html::beginTag('div', [ 'class' => 'box-header with-border' ]) . "\n";

            if ($this->collapsible === true)
            {
                echo Html::beginTag('div', [ 'class' => 'box-tools pool-right' ]);
                echo Html::beginTag('button', [ 'class' => 'btn btn-box-tool', 'data-widget' => 'collapse', 'data-include' => '.box-header' ]) . "\n";
                echo Html::beginTag('i', [
                        'class' => 'fa fa-' . $caret . ' pull-right',
                    ]) . "\n";
                echo Html::endTag("i");
                echo Html::endTag('button');
                echo Html::endTag('div');
            }

            echo $this->renderHeader() . "\n";
            echo Html::endTag('div');
        }
        echo $this->renderBodyBegin() . "\n";
    }


    /**
     * Initializes the widget options.
     * This method sets the default values for various options.
     */
    protected function initOptions()
    {
        $h             = $this->hidden ? ' hidden' : '';
        $s             = $this->sortable ? ' sortable' : '';
        $this->options = array_merge([
            'class' => $this->open ? 'box box-' . $this->type . $s . $h : 'box box-' . $this->type . ' collapsed-box' . $s . $h,
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
            $s = "<h3 class='box-title'>" . $this->title . "</h3>";

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
        $class = 'box-body collapse';
        if ($this->sortable)
        {
            $class .= ' non-sortable';
        }
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
