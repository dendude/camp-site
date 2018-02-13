<?php
namespace app\modules\office\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class OfficeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/css/office.css',
    ];
    public $js = [
        '/js/office.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
        'app\assets\ColorBoxAsset'
    ];
}
