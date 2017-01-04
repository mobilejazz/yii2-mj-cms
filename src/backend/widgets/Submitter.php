<?php

namespace mobilejazz\yii2\cms\backend\widgets;

use Yii;
use yii\bootstrap\Html;
use yii\bootstrap\Widget;
use yii\db\ActiveRecord;
use yii\web\HttpException;

class Submitter extends Widget
{

    /**
     * @var  $model ActiveRecord
     */
    public $model = null;

    public $returnUrl = '/admin/index';

    public $displayCancel = true;

    public $displayDelete = true;

    public $displaySave = true;

    public $cancelType = 'default';


    public function init()
    {
        parent::init();
        $this->initOptions();

        if (!isset($this->model))
        {
            throw new HttpException(400, Yii::t('backend', 'No model has been specified'));
        }

        echo Html::beginTag('div', $this->options);

        if ($this->displayCancel)
        {
            if ($this->cancelType == 'default')
            {
                echo Html::a('<i class="fa fa-times icon-margin small"></i> ' . Yii::t('backend', 'Cancel'), $this->returnUrl,
                    [ 'class' => 'btn btn-default' ]);
            }
            else if ($this->cancelType == 'modal')
            {
                echo "<button type=\"button\" class=\"btn btn-default pull-left\" data-dismiss=\"modal\"><i class=\"fa fa-times icon-margin small\"></i>" . Yii::t('backend',
                        'Cancel') . "</button>";
            }
        }

        if ($this->displayDelete && !$this->model->isNewRecord)
        {
            echo Html::a('<span class="fa fa-trash icon-margin small"></span> ' . Yii::t('backend', 'Delete'), [ 'delete', 'id' => $this->model->id ],
                [
                    'class' => 'btn btn-danger',
                    'data'  => [
                        'confirm' => Yii::t('backend', 'Are you sure you want to delete this? Please don\'t do anything you later regret.'),
                        'method'  => 'post',
                    ],
                    'style' => $this->displayCancel ? 'margin-left: 5px;' : '',
                ]);
        }

        if ($this->displaySave)
        {
            if ($this->model->isNewRecord)
            {
                echo Html::submitButton('<span class="fa fa-check icon-margin small"></span> ' . Yii::t('backend', 'Create'),
                    [ 'class' => 'btn btn-success pull-right' ]);
            }
            else
            {
                echo Html::submitButton('<span class="fa fa-refresh icon-margin small"></span> ' . Yii::t('backend', 'Update'),
                    [ 'class' => 'btn btn-success pull-right' ]);
            }
        }
        echo "<div class=\"clearfix\"></div>";
        echo Html::endTag('div');
    }


    protected function initOptions()
    {
        $this->options = array_merge([ 'class' => 'form-group', ], $this->options);
    }
}