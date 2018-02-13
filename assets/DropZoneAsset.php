<?php

namespace app\assets;

use yii\web\AssetBundle;

class DropZoneAsset extends AssetBundle
{
    public $basePath = '@bower/dropzone/dist';
    public $baseUrl = '/lib/dropzone/dist';

    public $css = [
        'basic.css',
        'dropzone.css',
    ];
    public $js = [
        'dropzone.js',
    ];

    public $depends = [
        'app\assets\AppAsset',
    ];
}