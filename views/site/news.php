<?php
use app\helpers\MetaHelper;
use yii\widgets\ActiveForm;
use app\models\Pages;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\forms\SearchForm;
use app\models\LocCountries;
use app\models\LocRegions;
use yii\widgets\LinkPager;
use app\models\forms\UploadForm;
use app\helpers\Normalize;

/**
 * @var $this yii\web\View
 * @var $model \app\models\Pages
 * @var $news \app\models\News[]
 * @var $pages \yii\data\Pagination
 */

$this->title = $model->title;
$this->params['breadcrumbs'] = [
    $model->title
];

MetaHelper::setMeta($model, $this);
?>
<div class="layout-container">
    <h1 class="index-title m-t-0"><?= $model->title ?></h1>
    <? if ($news): ?>
        <div class="news-sections">
            <? foreach ($news AS $n): ?>
            <section class="new-item">
                <div class="new-thumb">
                    <a href="<?= Url::to(['/site/new', 'alias' => $n->alias]) ?>" title="Подробнее">
                        <img src="<?= UploadForm::getSrc($n->photo, UploadForm::TYPE_NEWS, '_sm') ?>" alt="<?= Html::encode($n->title) ?>" />
                    </a>
                </div>
                <div class="new-content">
                    <h2 class="new-title"><a href="<?= Url::to(['/site/new', 'alias' => $n->alias]) ?>"><?= Html::encode($n->title) ?></a></h2>
                    <p class="new-about"><?= str_replace(PHP_EOL, ' ', Html::encode($n->about)) ?></p>
                    <div class="new-info">
                        <span class="new-date"><?= Normalize::getDateByTime($n->created) ?></span>
                        <span class="new-link"><a href="<?= Url::to(['/site/new', 'alias' => $n->alias]) ?>">Подробнее &raquo;</a></span>
                    </div>
                </div>
            </section>
            <? endforeach; ?>
        </div>
        <div class="text-center">
            <?= LinkPager::widget(['pagination' => $pages]);?>
        </div>
    <? else: ?>
        <div class="alert alert-info m-t-30 m-b-30">Новости не найдены</div>
    <? endif; ?>
</div>