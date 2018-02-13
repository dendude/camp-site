<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class MailAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/lib/font-awesome/css/font-awesome.min.css',
        '/fonts/sourcesanspro/sourcesanspro.css',
        
        'css/fonts.css',
        'css/base.css',
        'css/mail.css',
    ];
    public $js = [];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}