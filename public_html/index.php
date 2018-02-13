<?php

$admin_ips = ['::1','127.0.0.1','94.19.219.69','78.140.198.50'];
if (in_array($_SERVER['REMOTE_ADDR'], $admin_ips)) {
    // comment out the following two lines when deployed to production
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_ENV') or define('YII_ENV', 'dev');
}

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();
