<?php

namespace mobilejazz\yii2\cms\backend\widgets;

use yii;
use yii\grid\GridView;

/**
 * This widget helps build GridViews in the desired way and with
 * the desired functionality within the Yii2 Base project.
 *
 * It tries to implement some of the basic functionality we are re-using
 * throughout the project by defining some easy configurations:
 *
 * 1- Create: default true. Redirects you to a create button of the current Model.
 * 2- TableOptions: default 'table table-striped'. Classes that apply to the actual Grid.
 * 3- HeaderRowOptions: Classes that apply to the header row (ti¡tle).
 * 4- Pager: pager to implement. If none is defined we will use our custom pager widget (which we created a while ago).
 * 5- Layout: The layout to apply to the GridView after the _k Actions Row.
 * 6- Bulk Actions: default true, accepts array for bulk actions with this schema: [ 'controllerAction' => 'stringtoDisplay']
 * 7- SearchModel: if you want search features for the model, please add the Search Model you want to look for.
 * 8- SearchField: which field within that model do you specifically want to look for?
 * 9- Columns: the columns for the GridView.
 *
 * Class ExpandedGridView
 * @package backend\widgets
 */
class ExpandedGridView extends GridView
{

    public $id = null;

    /**
     * @var bool Should we add a button that links directly to
     *     the create section of this gridview?
     */
    public $create = true;

    /**
     * @var array Table Options
     */
    public $tableOptions = [ 'class' => 'table table-striped' ];

    /**
     * @var array Header row options
     */
    public $headerRowOptions = [ 'class' => '' ];

    /**
     * @var null Pager Properties
     */
    public $pager = null;

    /**
     * @var string The default layout of this GridView
     */
    public $layout = '{pager}{items}';

    /**
     * @var array Bulk Actions dropdown and actions.
     */
    public $bulk_actions = true;

    /**
     * @var string The search model to look for.
     */
    public $searchModel = null;

    /**
     * @var string The field of the model to look for.
     */
    public $searchField = null;

    /**
     * @var array
     */
    public $columns = null;

    /**
     * @var
     */
    public $bulk_action_base_url;


    public function init()
    {
        // $this->filterSelector = 'select[name="per-page"]';
        if (!isset($this->bulk_action_base_url))
        {
            $this->bulk_action_base_url = Yii::$app->request->url;
        }

        parent::init();

        // Set the pager if not there already.
        if ($this->pager == null)
        {
            $this->pager = [
                //'class'   => yii\widgets\LinkPager::className(),
                'class'   => LinkPager::className(),
                'options' => [
                    'class' => 'pagination',
                    'style' => 'display: inline',
                ],
            ];
        }

        // Set the bulk actions if not there already.
        if ($this->bulk_actions === true || $this->bulk_actions === null)
        {
            $this->bulk_actions = [
                ''       => Yii::t('backend', 'Bulk Actions'),
                'delete' => Yii::t('backend', 'Delete'),
            ];
        }
    }


    public function run()
    {
        echo $this->render('quick_tools', [
            'create'               => $this->create,
            'bulk_actions'         => $this->bulk_actions,
            'bulk_action_base_url' => $this->bulk_action_base_url,
            'search'               => $this->search(),
        ]);
        parent::run();
    }


    /**
     * @return bool|string false if search is disallowed, else
     *     return the query.
     */
    private function search()
    {
        if ($this->searchModel != null && $this->searchField != null)
        {
            $sm = $this->searchModel;
            $sf = $this->searchField;

            return "?" . $sm . "[" . $sf . "]";
        }

        return false;
    }
}