<?php
use app\helpers\MetaHelper;
use yii\widgets\ActiveForm;
use app\models\Pages;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\forms\SearchForm;
use yii\widgets\LinkPager;
use app\models\forms\UploadForm;
use app\helpers\Normalize;
use app\models\TagsTypes;

/* @var $this yii\web\View */
/* @var $model \app\models\Pages */
/* @var $models \app\models\Camps[] */
/* @var $pages \yii\data\Pagination */

$this->title = $model->title;
$this->params['id'] = $model->id;

MetaHelper::setMeta($model, $this);

$this->params['breadcrumbs'] = [
    ['label' => 'Все лагеря', 'url' => ['/camps']],
    'Для групп'
];
?>
<div class="layout-container">
    <?= \app\widgets\CampSearch::widget() ?>
    
    <div class="hot-tickets">
        <div class="border-title">
            <div class="row">
                <div class="col-xs-12 col-sm-3">
                    <h1 class="camp-result-title"><?= Html::encode($this->title) ?></h1>
                </div>
                <div class="col-xs-12 col-sm-9">
                    <span class="camp-found-count">
                        Найдено <strong><?= Normalize::wordAmount($pages->totalCount, ['лагерей','лагерь','лагеря'], true) ?></strong>
                    </span>
                </div>
            </div>
        </div>
        <? if ($models): ?>
            <? foreach ($models AS $m): ?>
                <?= $this->render('camp-item', ['m' => $m]) ?>
            <? endforeach; ?>
        <? endif; ?>
        <?= LinkPager::widget(['pagination' => $pages]);?>
    </div>
</div>