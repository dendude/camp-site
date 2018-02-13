<?php
namespace app\assets;

use yii\web\AssetBundle;

class ColorBoxAsset extends AssetBundle
{
    public $sourcePath = '@bower/colorbox';
    public $css = [
        'example3/colorbox.css',
    ];
    public $js = [
        'jquery.colorbox.js',
        'i18n/jquery.colorbox-ru.js',
    ];

    public $depends = [
        'app\assets\AppAsset',
    ];
}