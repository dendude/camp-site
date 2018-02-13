<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\components\ReCaptcha;

AppAsset::register($this);

$meta_title = !empty($this->params['meta_t']) ? $this->params['meta_t'] : $this->title;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="/img/favicon-192x192.jpg" type="image/x-icon" />
    
    <?= Html::csrfMetaTags() ?>
    
    <title><?= Html::encode(!empty($this->params['meta_t']) ? $this->params['meta_t'] : $this->title) ?></title>
    <? if (!empty($this->params['meta_d'])): ?><meta name="description" content="<?= Html::encode($this->params['meta_d']) ?>"><? endif; ?>
    <? if (!empty($this->params['meta_k'])): ?><meta name="keywords" content="<?= Html::encode($this->params['meta_k']) ?>"><? endif; ?>
    
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async="async" defer="defer"></script>
    <script type="text/javascript" src="//vk.com/js/api/openapi.js?146"></script>
    
    <script type="text/javascript">
        var onloadCallback = function() {
            grecaptcha.render('widget_review', {'sitekey' : '<?= ReCaptcha::PUBLIC_KEY ?>'});
        }
    </script>
    
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="layout">
    <?= $this->render('_header') ?>
    <div class="layout-container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
    </div>
    <div class="content"><?= $content ?></div>
</div>
<?= $this->render('_footer') ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
