<?php

use dosamigos\fileupload\FileUploadUI;

echo FileUploadUI::widget([
    'model'     => $model,
    'attribute' => 'file',
    'url'       => [ 'upload' ],
    'gallery'   => false,
]);