<div role="filemanager-modal" class="modal" tabindex="-1"
     data-frame-id="<?= $frameId ?>"
     data-frame-src="<?= $frameSrc ?>"
     data-btn-id="<?= $btnId ?>"
     data-input-id="<?= $inputId ?>"
     data-image-container="<?= isset($imageContainer) ? $imageContainer : '' ?>"
     data-paste-data="<?= isset($pasteData) ? $pasteData : '' ?>"
     data-thumb="<?= $thumb ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= Yii::t('backend', 'File Manager') ?></h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('backend', 'Close') ?></button>
            </div>
        </div>
    </div>
</div>