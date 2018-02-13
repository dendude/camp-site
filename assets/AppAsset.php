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
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/lib/font-awesome/css/font-awesome.min.css',
        '/lib/AdminLTE/plugins/iCheck/square/blue.css',
        '/lib/AdminLTE/plugins/datepicker/datepicker3.css',
        '/lib/AdminLTE/plugins/select2/select2.min.css',
        
        '/lib/tiny-slider/dist/tiny-slider.css',
                
        '//fonts.googleapis.com/css?family=PT+Sans:regular,italic,bold,bolditalic',
        '/fonts/sourcesanspro/sourcesanspro.css',
        
        'css/fonts.css?3',
        'css/base.css?6',
        'css/site.css?6',
        'css/adaptive.css?6',
    ];
    public $js = [
        'https://api-maps.yandex.ru/2.1/?lang=ru_RU',
        
        '/lib/html5shiv/dist/html5shiv.js',
        '/lib/tiny-slider/dist/tiny-slider.js',
        
        '/lib/AdminLTE/plugins/iCheck/icheck.min.js',
        '/lib/AdminLTE/plugins/datepicker/bootstrap-datepicker.js',
        '/lib/AdminLTE/plugins/datepicker/locales/bootstrap-datepicker.ru.js',

        '/lib/AdminLTE/plugins/select2/select2.min.js',
        '/lib/AdminLTE/plugins/select2/i18n/ru.js',
        
        '/js/main.js?6',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
