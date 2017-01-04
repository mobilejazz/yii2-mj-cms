<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var bool         $create
 * @var array|bool   $bulk_actions
 * @var string       $bulk_action_base_url
 * @var bool|string  $search
 */
?>
    <div class="form-inline">
        <?php if ($create && is_bool($create)): ?>
            <!-- ADD NEW MODEL -->
            <div class="form-group add-margin-right">
                <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('backend', 'Add New'), [ 'create' ],
                    [ 'class' => 'btn btn-primary' ]) ?>
            </div>
        <?php elseif ($create): ?>
            <div class="form-group add-margin-right">
                <?= $create ?>
            </div>
        <?php endif; ?>

        <?php if ($bulk_actions != false): ?>
            <!-- BULK ACTIONS -->
            <div class="form-group add-margin-right">
                <?= Html::dropDownList('bulk-action', null, $bulk_actions, [
                    'id'    => 'bulk-dropdown',
                    'class' => 'form-control',
                ]) ?>
                <?= Html::button("<i class=\"fa fa-check icon-margin small\"></i> " . Yii::t("backend", "Apply"), [
                    'id'    => 'bulk-action-submit',
                    'class' => 'btn btn-default',
                ]) ?>
            </div>
        <?php endif; ?>

        <?php if ($search): ?>
            <!-- SEARCH -->
            <div class="form-group">
                <?= Html::textInput('search', null, [
                    'id'          => 'web-form-text-box',
                    'class'       => 'form-control',
                    'placeholder' => Yii::t('backend', 'Search'),
                ]) ?>
                <?= Html::button("<i class=\"fa fa-search icon-margin small\"></i> " . Yii::t('backend', 'Search'), [
                    'id'    => 'web-form-search-btn',
                    'class' => 'btn btn-default',
                ]) ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="clearfix"></div>

<?php if ($search || $bulk_actions || $create): ?>
    <!-- Spacer -->
    <hr>
<?php endif; ?>
<?php
// Set the return url for the bulk action.
$referer = str_replace("/index", "", $bulk_action_base_url);
$referer = strtok($referer, '?');
$url     = $referer . "/bulk";
if ($search)
{
    $searchScript = <<< JS
    // CONTENT SEARCH BY TITLE
    $('#web-form-text-box').keypress(function (e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            var text = $('#web-form-text-box').val();
            window.location.href = "$search=" + text;
        }
    });
    $('#web-form-search-btn').click(function () {
        var text = $('#web-form-text-box').val();
        window.location.href = "$search=" + text;
    });
JS;
    $this->registerJs($searchScript);
}

if ($bulk_actions)
{
    // JAVASCRIPT IN CHARGE OF BULK ACTIONS AND SEARCH ACTIONS.
    $bulkScript = <<< JS
    // BULK CONTENT BULK ACTIONS.
    $('#bulk-action-submit').click(function () {
        var sel = $('.grid-view').yiiGridView('getSelectedRows');
        var act = $('#bulk-dropdown').val();

        // Prevent content being deleted to happily.
        if (act == 'delete') {
            if (!confirm("Are you sure you want to delete this item/s?")) {
                return;
            }
        }
        console.log("$url");
        $.ajax({
            type: 'POST',
            url: "$url",
            data: {selection: sel, action: act}
        });
    });
JS;
    $this->registerJs($bulkScript);
}
?>