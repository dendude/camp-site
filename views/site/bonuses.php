<?php
use app\helpers\MetaHelper;
use yii\widgets\ActiveForm;
use app\models\Pages;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\forms\SearchForm;
use app\models\LocCountries;
use app\models\LocRegions;
use app\widgets\CampSearch;

/* @var $this yii\web\View */
/* @var $model \app\models\Pages */
/* @var $bonuses \app\models\Bonuses[] */

$this->title = $model->title;
MetaHelper::setMeta($model, $this);
?>
<div class="layout-container m-t-20">
    <? if ($model->isSearchFilter()): ?>
        <div class="page-with-filters">
            <div class="page-with-filters-content">
                <h1 class="page-title text-center m-t-0"><?= $model->title ?></h1>
                <article><?= $model->content ?></article>
    
                <div class="bonuses">
                    <? foreach ($bonuses AS $bonus): ?>
                        <div class="bonuses-item">
                            <div class="bonuses-icon">
                                <i class="fa <?= $bonus->icon_class ?>"></i>
                            </div>
                            <strong style="color: <?= $bonus->icon_color ?>">
                                <?= \app\helpers\Normalize::wordAmount($bonus->bonuses, ['баллов','балл','балла'], true) ?>
                            </strong>
                            <span><?= Html::encode($bonus->site_name) ?></span>
                        </div>
                    <? endforeach; ?>
                </div>
            </div>
            <div class="page-filters">
                <?= \app\widgets\CampRecommend::widget() ?>
                <?= CampSearch::widget(['type' => CampSearch::TYPE_COLUMN]) ?>
            </div>
        </div>
    <? else: ?>
        <h1 class="index-title text-center m-t-0"><?= $model->title ?></h1>
        <article><?= $model->content ?></article>
    
        <div class="bonuses">
            <? foreach ($bonuses AS $bonus): ?>
                <div class="bonuses-item">
                    <div class="bonuses-icon">
                        <i class="fa <?= $bonus->icon_class ?>"></i>
                    </div>
                    <strong style="color: <?= $bonus->icon_color ?>"><?= \app\helpers\Normalize::wordAmount($bonus->bonuses, ['баллов','балл','балла'], true) ?></strong>
                    <span><?= Html::encode($bonus->site_name) ?></span>
                </div>
            <? endforeach; ?>
        </div>
    <? endif; ?>
</div>