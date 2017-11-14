<?php
use mobilejazz\yii2\cms\common\models\ContentComponent;
use mobilejazz\yii2\cms\common\models\ContentSlug;
use mobilejazz\yii2\cms\common\models\ContentSource;
use mobilejazz\yii2\cms\common\models\Views;
use mobilejazz\yii2\cms\common\widgets\BoxPanel;
use mobilejazz\yii2\cms\backend\modules\filemanager\widgets\FileInput;
use unclead\multipleinput\MultipleInput;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;

/**
 * @var View               $this
 * @var ContentSlug        $slug
 * @var ContentComponent[] $components
 * @var array              $field_errors An array of errors for all fields.
 * @var ContentSource      $model
 */
$this->title = $model->isNewRecord ? Yii::t('backend', 'Add New Content') : Yii::t('backend', 'Content') . ': ' . $model->getTitle();

// Add the Add new Content button to the right of the title.
if (!$model->isNewRecord)
{
    $this->params[ 'subtitle' ] = Html::a('<i class="fa fa-plus icon-margin"></i> ' . Yii::t('backend', 'Create new Content'), 'create',
        [ 'class' => 'btn btn-primary' ]);
}
$this->params[ 'breadcrumbs' ][] = [
    'label' => Yii::t('backend', 'Content'),
    'url'   => [ 'index' ],
];
$this->params[ 'breadcrumbs' ][] = [
    'label' => (string) $model->getTitle(),
    'url'   => [
        'update',
        'id' => $model->id,
    ],
];
$this->params[ 'breadcrumbs' ][] = Yii::t('backend', 'Edit');
$form                            = ActiveForm::begin([
    'id' => 'content-source',
]);
?>
<!-- Content -->
<div class="row">
    <div class="col-xs-12 col-sm-9">
        <?php
        // ============================
        // CONTENT SOURCE RELATED
        // ============================
        BoxPanel::begin([
            'title'       => Yii::t('backend', 'Content Details'),
            'collapsible' => false,
        ]) ?>
        <?php if ($model->isNewRecord)
        {
            echo $form->field($model, 'view')
                      ->label(\Yii::t('backend', 'Template to apply'))
                      ->dropDownList(Views::asMap(), [ 'prompt' => Yii::t('backend', 'Select') ]);
        } ?>

        <?= $form->field($slug, 'title', [
            'options' => [
                'class' => 'input-group col-xs-6',
                'style' => 'padding: 5px; float: left;',
            ],
        ])
            ->textInput(['maxlength' => true, 'placeholder' => $slug->title])
            ->label(Yii::t('backend', 'Title')) ?>

        <label><?= Yii::t('backend', 'Slug') ?></label>

        <?= $form->field($slug, 'slug', [
            'options' => [
                'class' => 'input-group col-xs-6',
                'style' => 'padding: 5px; float: left;',
            ],
        ])
            ->textInput([
                'maxlength' => true,
                'placeholder' => $slug->slug,
                'readonly' => true,
            ])
            ->label(false) ?>

        <?php
        BoxPanel::end();

        if (isset($field_errors)) {

            if ($field_errors && (count($field_errors) > 0)) {
                ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-ban"></i> <?= Yii::t('backend', 'Alert!') ?></h4>
                    <?= Yii::t('backend', 'Attention, some fields have errors and have not been saved. Please review them.') ?>
                </div>
                <?php
            }
        }
        // ============================
        // CONTENT SOURCE RELATED
        // ============================
        if (!$model->isNewRecord) {
            ?>
            <div class='content-loader-activator hidden'>
                <div style='text-align:center; font-size: 33px;'>
                    <i class='fa fa-refresh fa-spin'></i> <?= Yii::t('backend', 'Loading...') ?>
                </div>
            </div>
            <div class="actualcontent">
                <?php
                echo $this->render("_content-view", [
                    'model'         => $model,
                    'components'    => $components,
                    'field_errors' => $field_errors
                ]);
                ?>
            </div>
            <?php

            // META TAGS START
            BoxPanel::begin([
                'title' => Yii::t('backend', 'SEO Meta Tags'),
                'collapsible' => false,
            ]);

            echo Alert::widget([
                'options' => [
                    'class' => 'alert-warning',
                ],
                'body' => \Yii::t('backend', 'Add as many Meta Tags as you wish. They will be rendered in the header of this content page.'),
            ]);

            echo $form->field($model, 'meta_tags')
                ->widget(MultipleInput::className(), [
                    'max' => 7,
                    'columns' => [
                        [
                            'name' => 'name',
                            'enableError' => true,
                            'title' => \Yii::t('backend', 'Meta Name'),
                            'options' => [
                                'style' => 'width: 250px;',
                            ],
                            'headerOptions' => [
                                'style' => 'width: 250px;',
                            ]
                        ],
                        [
                            'name' => 'content',
                            'enableError' => true,
                            'title' => \Yii::t('backend', 'Meta Content'),
                            'options' => [
                                'style' => 'width: 100%;',
                            ],
                            'headerOptions' => [
                                'style' => 'width: 100%;',
                            ]
                        ],
                    ]
                ])
                ->label(false);

            BoxPanel::end();
            // META TAGS END.
            // META RELS START
            BoxPanel::begin([
                'title' => Yii::t('backend', 'Content Relationships'),
                'collapsible' => false,
            ]);

            echo Alert::widget([
                'options' => [
                    'class' => 'alert-warning',
                ],
                'body' => \Yii::t('backend', 'Add as many Relationships as you wish. They will be rendered in the header of this content page.'),
            ]);

            echo $form->field($model, 'meta_rels')
                ->widget(MultipleInput::className(), [
                    'max' => 7,
                    'columns' => [
                        [
                            'name' => 'rel',
                            'enableError' => true,
                            'title' => \Yii::t('backend', 'Rel'),
                            'options' => [
                                'style' => 'width: 150px;',
                            ],
                            'headerOptions' => [
                                'style' => 'width: 150px;',
                            ]
                        ],
                        [
                            'name' => 'hreflang',
                            'enableError' => true,
                            'title' => \Yii::t('backend', 'Href Lang'),
                            'options' => [
                                'style' => 'width: 150px;',
                            ],
                            'headerOptions' => [
                                'style' => 'width: 150px;',
                            ]
                        ],
                        [
                            'name' => 'href',
                            'enableError' => true,
                            'title' => \Yii::t('backend', 'Href'),
                            'options' => [
                                'style' => 'width: 100%;',
                            ],
                            'headerOptions' => [
                                'style' => 'width: 100%;',
                            ]
                        ],
                    ]
                ])
                ->label(false);

            BoxPanel::end();
            // META TAGS END.

            BoxPanel::begin([
                'title' => Yii::t('backend', 'Slug history'),
                'collapsible' => false
            ]);

            $slugs = $model->getOldSlugs(Yii::$app->language);
            $label = Yii::t('backend', 'Previous Slugs');
            foreach ($slugs as $slug) {
                $lastSlug = end($slugs)->id === $slug->id;
                if($lastSlug){
                    $label = Yii::t('backend', 'Current Slug');
                }
                echo Html::label($label);
                echo '<pre>'.$slug->slug.'</pre>';
                if(!$lastSlug){
                    echo Html::checkbox('RemoveSlug[]',false,array('value' => $slug->id));
                    echo '&nbsp;&nbsp;'.Yii::t('backend', 'Remove Slug').' ?';
                    echo '<br/>';
                    echo '<br/>';
                }
                $label =  '';
            }

            BoxPanel::end();
        }
        ?>

        <?php if ($model->isNewRecord): ?>
            <?= Html::a('<span class="glyphicon glyphicon-check"></span> ' . Yii::t('backend', 'Create'), ['create', 'id' => $model->id], [
                'class' => 'btn btn-success',
                'data' => [
                    'method' => 'post',
                ],
            ]); ?>
        <?php endif ?>
    </div>

    <?php if (!$model->isNewRecord): ?>
        <div class="col-xs-12 col-sm-3 affix-element actionsContainer">
            <?php BoxPanel::begin([
                'title' => Yii::t('backend', 'Publishing'),
                'collapsible' => false,
            ]) ?>
            <div class="form-group">
                <?php if (!$model->is_homepage): ?>
                    <?= Html::a('<i class="fa fa-home icon-margin small"></i>' . \Yii::t('backend', 'Make Home Page'),
                        ['make-homepage', 'id' => $model->id], [
                            'class' => 'btn btn-primary',
                            'style' => 'display: block; margin-bottom: 10px;',
                        ]) ?>
                <?php endif; ?>
            </div>
            <!-- PUBLISHED? -->
            <?= $form->field($model, 'status')
                ->dropDownList(ContentSource::status())
                ->label(Yii::t('backend', 'Status')) ?>


            <?= $form->field($model, 'sort', [
                'options' => [
                    'class' => 'form-group',
                ],
            ])
                ->input('number',['maxlength' => true, 'placeholder' => $model->sort])
                ->label(Yii::t('backend', 'Sort')) ?>

            <?= $form->field($model, 'thumbnail',[
                'options' => [
                    'class' => 'form-group',
                ]
            ])->widget(FileInput::className(), [

                'buttonTag'            => 'button',
                'buttonName'           => 'Browse',
                'buttonOptions'        => [ 'class' => 'btn btn-default' ],
                'options'              => [ 'class' => 'form-control' ],
                // Widget template
                'template'             => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
                'thumb'                => 'original',
                'pasteData'            => FileInput::DATA_URL,
                'callbackBeforeInsert' => 'function(e, data) {
        console.log(data);
        console.log(e);
        }',
            ]);
            ?>

            <!-- PUBLISH DATE -->
            <!-- <? /*= $form->field($model, 'publish_date_string')->widget(DatePicker::className(), [
                'value'         => date('today'),
                'options'       => [ 'placeholder' => \Yii::t('backend', 'Please set a date'), ],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format'    => 'dd-M-yyyy'
                ]
            ]); */ ?>
            -->

            <div class="form-group form-inline add-top-margin viewButtons">
                <?= Html::a('<i class="fa fa-eye icon-margin small"></i> ' . Yii::t('backend', 'View Content'),
                    $this->context->module->urlManagerFrontend->createBaseUrl('cmsfrontend/site/content', [
                        'lang' => Yii::$app->language,
                        'slug' => $slug->slug,
                        $this->context->module->previewService->url_param => $this->context->module->previewService->getToken()
                    ]), [
                        'class' => 'btn btn-default viewContent',
                        'target' => '_blank',
                        'style' => 'margin-right: 10px;',
                    ]) ?>
                <?= Html::a('<i class="fa fa-bullseye"></i> ' . Yii::t('backend', 'Quick View'), false, [
                    'data-value' => Url::to([
                        'quick-view',
                        'id' => $model->id,
                    ]),
                    'label' => Yii::t('backend', 'Content Structure') . " " . $slug->title,
                    'class' => 'showModalButton btn btn-primary',
                ]) ?>
            </div>
            <div class="clearfix"></div>
            <div class="form-group form-inline actionButtons">
                <!-- Save the Content -->
                <?= Html::a('<span class="fa fa-check icon-margin small"></span> ' . Yii::t('backend', 'Save'), ['update', 'id' => $model->id], [
                    'class' => 'btn btn-success',
                    'style' => 'margin-right: 10px;',
                    'data' => [
                        'method' => 'post',
                    ],

                ]); ?>

                <!-- Delete the Content -->
                <?= Html::a('<span class="fa fa-trash icon-margin small"></span> ' . Yii::t('backend', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger delete-content',
                    'data' => [
                        'method' => 'post',
                    ],
                ]); ?>
            </div>
            <?php BoxPanel::end() ?>
        </div>
    <?php endif ?>
</div>
<?php
\Yii::$app->session->set("content-source-form", ArrayHelper::toArray($form));
ActiveForm::end();

$delete_msg = '<span class="fa fa-trash icon-margin small"></span> ' . \Yii::t('backend', 'Are you sure?');
$script = <<< JS
// CONTENT SLUG EDIT.
var cs = $('#contentslug-slug');
cs.parent().addClass('form-inline');
cs.after("<span style='margin-left: 3px' id=\"content-slug-enabler\" class=\"input-group-addon\"><i class=\"fa fa-pencil icon-margin small\"></i></span>");
$('#content-slug-enabler').click(function () {
    var ti = $('#contentslug-slug');
    if (ti.attr('readonly')) {
        ti.removeAttr('readonly');
    }
    else ti.attr('readonly', 'true');
});

// DELETION OF STUFF.
$('a.delete-content').off('click');
$('a.delete-content').on('click', function (event) {
    event.preventDefault();
    event.stopPropagation();

    var href = $(this).attr('href'),
        target = ( $(this).attr('target') ? $(this).attr('target') : '_self' ),
        msg = $(this).attr('data-confirm');

    $(this).html('$delete_msg');

    $(this).on('click', function (event) {
        window.open(href, target);
    });
});
JS;
$this->registerJs($script);
?>
<!-- /.content -->


