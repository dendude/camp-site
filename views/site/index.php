<?php
use app\helpers\MetaHelper;
use app\models\Pages;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\forms\SearchForm;
use app\models\forms\UploadForm;
use app\models\Camps;

/* @var $this yii\web\View */
/* @var $model \app\models\Pages */
/* @var $search SearchForm */

$this->title = $model->title;
$this->params['id'] = $model->id;

MetaHelper::setMeta($model, $this);

/** @var $selections \app\models\Selections[] */
$selections = \app\models\Selections::find()->active()->ordering()->all();
if ($selections):
?>
<div class="bg-gray-light">
    <div class="layout-container">
        <div class="search-container">
            <div class="search-block">
                <h1 class="index-title text-center"><?= $model->title ?></h1>
                <?= \app\widgets\CampSearch::widget() ?>
            </div>
        </div>
    </div>
</div>

<div class="bg-gray">
    <div class="layout-container">
        <div class="camp-selection">
            <h2 class="index-title text-center">
                <a href="<?= Pages::getUrlById(Pages::PAGE_SELECTIONS) ?>">Подборки детских лагерей</a>
            </h2>
            <div class="slider-container">
                <ul class="slider-selection">
                    <? for ($i = 0; $i < count($selections); $i += 2): ?>
                        <li>
                            <? for ($j = 0; $j <= 1; $j++): ?>
                            <?
                                if (!isset($selections[$i+$j])) break;
                                $sel_model = $selections[$i+$j];
                            ?>
                            <a href="<?= Url::to(['/site/camps', 'type' => Camps::TYPE_TYPE, 'alias' => $sel_model->type->alias]) ?>">
                                <span class="camp-orders-photo">
                                    <img src="<?= UploadForm::getSrc($sel_model->photo, UploadForm::TYPE_PAGES, '_md') ?>"
                                         alt="<?= Html::encode($sel_model->type->title_full) ?>" />
                                </span>
                                <span class="camp-orders-title"><?= Html::encode($sel_model->type->title_full) ?></span>
                            </a>
                            <? endfor; ?>
                        </li>
                    <? endfor; ?>
                </ul>
                
                <div class="slider-controls slider-controls-selection">
                    <span class="slide-prev"></span>
                    <span class="slide-next"></span>
                </div>
            </div>
        </div>
    </div>
</div>
    
<?php
$this->registerJs("
tns({
    container: document.querySelector('.slider-selection'),
    controlsContainer: document.querySelector('.slider-controls-selection'),    
    autoplayHoverPause: true,
    controls: true,
    autoplay: true,
    nav: false,
    speed: 750,
    items: 4,
    responsive: {
        150: 1,
        600: 2,
        800: 3,
        1100: 4
    }
});
");
?>
    
<? endif; ?>

<div class="m-t-30 m-b-50">
    <div class="layout-container">
        <h2 class="index-title text-center">Мы рекомендуем</h2>
        
        <? $settings = \app\models\Settings::lastSettings(); ?>
        <?= \app\widgets\CampRecommend::widget(['limit' => $settings->camps_main_count]) ?>
    </div>
</div>

<div class="bg-gray">
    <div class="layout-container">
        <?= \app\widgets\CampOrders::widget() ?>
    </div>
</div>

<?
/** @var $ratings Camps[] */
/*
$ratings = Camps::find()->active()->rating()->orderByRating()->limit(10)->all();
if ($ratings):
?>
<div class="layout-container">
    <div class="camp-rating">
        <h2 class="index-title text-center">Рейтинг детских лагерей /по отзывам/</h2>
        <div class="camp-index-slider camp-orders-slider">
            <div class="camp-orders-slider-container">
                <div class="camp-orders-slider-inside">
                    <ul>
                        <? foreach ($ratings AS $k => $m): ?>
                            <li>
                                <a href="<?= $m->getCampUrl() ?>">
                                    <span class="camp-orders-photo">
                                        <span class="camp-orders-number"><?= ($k+1) ?></span>
                                        <img src="<?= UploadForm::getSrc($m->media->photo_main, UploadForm::TYPE_CAMP, '_md') ?>" alt="<?= Html::encode($m->about->name_short) ?>" />
                                    </span>
                                    <span class="camp-orders-title"><?= Html::encode($m->about->name_short) ?></span>
                                    <span class="camp-orders-location"><?= $m->about->country->name ?>, <?= $m->about->city->name ?></span>
                                </a>
                            </li>
                        <? endforeach; ?>
                    </ul>
                </div>
            </div>
            <span class="slide-prev"></span>
            <span class="slide-next"></span>
        </div>
    </div>
</div>
<? endif;*/ ?>

<!--<div class="layout-container m-t-30 m-b-30">
    <a href="<?/*= Pages::getUrlById(Pages::PAGE_REVIEWS_ID) */?>" class="but but-reviews">Отзывы о лагерях</a>
</div>-->

<?/*= \app\widgets\CampsNew::widget(); */?>