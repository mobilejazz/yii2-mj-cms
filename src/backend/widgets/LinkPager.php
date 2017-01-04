<?php

namespace mobilejazz\yii2\cms\backend\widgets;

use Yii;
use yii\helpers\Html;

class LinkPager extends \yii\widgets\LinkPager
{

    /**
     * {pageButtons} {customPage} {pageSize}
     */
    public $template = '{pageButtons} {pageSize}';

    /**
     * pageSize list
     */
    public $pageSizeList = [ 10, 20, 30, 50 ];

    /**
     *
     * Margin style for the  pageSize control
     */
    public $pageSizeMargin = "margin-left:5px;margin-right:5px;height: 31px;";

    /**
     * customPage width
     */
    public $customPageWidth = 50;

    /**
     * Margin style for the  customPage control
     */
    public $customPageMargin = "margin-left:5px;margin-right:5px;height: 31px;";

    /**
     * Jump
     */
    public $customPageBefore = '';

    /**
     * Page
     */
    public $customPageAfter = "";

    /**
     * pageSize style
     */
    public $pageSizeOptions = [ 'class' => 'form-control', 'style' => 'display: inline;width:auto;' ];

    /**
     * customPage style
     */
    public $customPageOptions = [ 'class' => 'form-control', 'style' => 'display: inline;margin-top:0px;' ];


    public function init()
    {
        parent::init();
        if ($this->pageSizeMargin)
        {
            Html::addCssStyle($this->pageSizeOptions, $this->pageSizeMargin);
        }
        if ($this->customPageWidth)
        {
            Html::addCssStyle($this->customPageOptions, 'width:' . $this->customPageWidth . 'px;');
        }
        if ($this->customPageMargin)
        {
            Html::addCssStyle($this->customPageOptions, $this->customPageMargin);
        }
    }


    /**
     * Executes the widget.
     * This overrides the parent implementation by displaying the generated page buttons.
     */
    public function run()
    {
        if ($this->registerLinkTags)
        {
            $this->registerLinkTags();
        }
        echo $this->renderPageContent();
    }


    protected function renderPageContent()
    {
        return preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches)
        {
            $name = $matches[ 1 ];
            if ('customPage' == $name)
            {
                return $this->renderCustomPage();
            }
            else if ('pageSize' == $name)
            {
                return $this->renderPageSize();
            }
            else if ('pageButtons' == $name)
            {
                return $this->renderPageButtons();
            }

            return "";
        }, $this->template);
    }


    protected function renderCustomPage()
    {
        $page   = 1;
        $params = Yii::$app->getRequest()->queryParams;
        if (isset($params[ $this->pagination->pageParam ]))
        {
            $page = intval($params[ $this->pagination->pageParam ]);
            if ($page < 1)
            {
                $page = 1;
            }
            else if ($page > $this->pagination->getPageCount())
            {
                $page = $this->pagination->getPageCount();
            }
        }

        return $this->customPageBefore . Html::textInput($this->pagination->pageParam, $page, $this->customPageOptions) . $this->customPageAfter;
    }


    protected function renderPageSize()
    {
        $pageCount = $this->pagination->getPageCount();
        if ($pageCount < 2 && $this->hideOnSinglePage)
        {
            return '';
        }

        $pageSizeList = [ ];
        foreach ($this->pageSizeList as $value)
        {
            $pageSizeList[ $value ] = $value;
        }

        return Html::dropDownList($this->pagination->pageSizeParam, $this->pagination->getPageSize(), $pageSizeList, $this->pageSizeOptions);
    }

}
