<?php

namespace app\assets;

use yii\web\AssetBundle;

class UploadAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery-file-upload2';
    public $css = [
        'css/jquery.fileupload.css',
        'css/jquery.fileupload-ui.css',
        'css/jquery.fileupload-noscript.css',
    ];
    public $js = [
        'js/vendor/jquery.ui.widget.js',
        'js/jquery.iframe-transport.js',
        'js/jquery.fileupload.js',
        '/js/admin.js',
    ];
}