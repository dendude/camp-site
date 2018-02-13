<?php

namespace app\modules\manage\assets;

use yii\web\AssetBundle;

class ManageAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        '/lib/colorbox/example3/colorbox.css',
        '/lib/smalot-bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',
        
        'css/base.css',
        'css/manage.css',
    ];
    public $js = [
        '/lib/colorbox/jquery.colorbox.js',
        '/lib/colorbox/i18n/jquery.colorbox-ru.js',

        '/lib/smalot-bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',
        '/lib/smalot-bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.ru.js',
        
        'js/main.js?2',
        'js/manage.js?2',
    ];

    public $depends = [
        'app\modules\manage\assets\LTEAsset',
        'yii\web\YiiAsset',
    ];
}