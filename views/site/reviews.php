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
/* @var $models \app\models\Reviews[] */
/* @var $pages \yii\data\Pagination */

$this->title = $model->title;
MetaHelper::setMeta($model, $this);

$this->params['breadcrumbs'] = [
    ['label' => 'Все лагеря', 'url' => ['/camps']],
    $model->title
];
?>
<div class="layout-container">
    <?= \app\widgets\CampSearch::widget() ?>

    <div class="camp-reviews-container">
        <div class="camp-reviews">
            <? foreach ($models AS $r): ?>
                <div class="camp-review-item">
                    <h2 class="camp-name">
                        <a href="<?= $r->camp->getCampUrl() ?>"><?= Html::encode($r->camp->about->name_short) ?></a>
                    </h2>
                    <span class="camp-location">
                        <?= Html::encode("{$r->camp->about->country->name} / {$r->camp->about->region->name}") ?>
                    </span>
                    <div class="row">
                        <div class="col-xs-12 col-md-3">
                            <a class="camp-image" href="<?= $r->camp->getCampUrl() ?>" title="<?= Html::encode($r->camp->about->name_short) ?>">
                                <img src="<?= UploadForm::getSrc($r->camp->media->photo_main, UploadForm::TYPE_CAMP, '_md') ?>"
                                     alt="<?= Html::encode($r->camp->about->name_short) ?>" width="100%"/>
                            </a>
                        </div>
                        <div class="col-xs-12 col-md-9">
                            <span class="review-name">
                                <?= Normalize::getStarsIcons($r->stars); ?>
                                <?= Html::encode($r->user_name) ?>
                            </span>
                            <span class="review-date"><?= Normalize::getFullDateByTime($r->created) ?></span>
                            <div class="row">
                                <div class="col-xs-12 col-md-6">
                                    <p class="review-text review-text-positive">
                                        <span class="review-type">Преимущества</span>
                                        <?= Html::encode($r->comment_positive) ?>
                                    </p>
                                    <? if ($r->comment_manager): ?>
                                        <p class="review-text review-text-manager">
                                            <span class="review-type">Комментарий менеджера</span>
                                            <?= nl2br(Html::encode($r->comment_manager)) ?>
                                        </p>
                                    <? endif; ?>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <? if ($r->comment_negative): ?>
                                        <p class="review-text review-text-negative">
                                            <span class="review-type">Недостатки</span>
                                            <?= Html::encode($r->comment_negative) ?>
                                        </p>
                                    <? endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            <? endforeach; ?>
        </div>

        <div class="m-t-20">
            <?= LinkPager::widget(['pagination' => $pages]);?>
        </div>
    </div>
</div>