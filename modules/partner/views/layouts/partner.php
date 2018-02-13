<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\modules\office\assets\OfficeAsset;

$cab_name = 'Кабинет партнера';

OfficeAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="<?= Yii::$app->request->csrfToken ?>"/>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="layout p-b-30">
    <?= $this->render('//layouts/_header') ?>
    <div class="layout-container">
        <? if (!isset($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => [$cab_name]]) ?>
        <? else: ?>
            <?= Breadcrumbs::widget([
                'links' => array_merge([[
                    'label' => $cab_name, 'url' => ['main/index']
                ]], $this->params['breadcrumbs']),
            ]) ?>
        <? endif; ?>
    </div>
    <div class="content">
        <div class="layout-container">
            <div class="row">
                <div class="col-xs-3"><?= $this->render('_sidebar') ?></div>
                <div class="col-xs-9">
                    <h1 class="office-title"><?= $this->title ?></h1>
                    <?= $content ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->render('//layouts/_footer') ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
