<?php
use app\helpers\MetaHelper;
use app\models\LocCountries;
use app\models\LocRegions;
use app\models\Camps;
use app\helpers\CampHelper;
use yii\helpers\Url;
use app\widgets\CampSearch;
use app\models\forms\UploadForm;
use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var $model \app\models\Pages
 */

$this->title = $model->title;
$this->params['id'] = $model->id;
$this->params['breadcrumbs'] = [
    $model->title
];

MetaHelper::setMeta($model, $this);
?>
<div class="layout-container p-t-20">
    
    <div class="page-with-filters">
        <div class="page-with-filters-content">
            <h1 class="page-title m-t-0"><?= $model->title ?></h1>
            <article><?= $model->content ?></article>
    
            <ul class="catalog-list">
                <li>
                    <? foreach (LocCountries::getFilterListWithCamps() AS $country_id => $country_name): ?>
                        <? if ($country_id == LocCountries::DEFAULT_ID): ?>
                            <h2><a href="<?= Url::to(['camps', 'type' => Camps::TYPE_COUNTRY, 'alias' => LocCountries::getAliasById($country_id)]) ?>"><?= $country_name ?></a></h2>
                            <ul>
                                <? foreach (LocRegions::getFilterListWithCamps($country_id) AS $region_id => $region_name): ?>
                                    <li>
                                        <h3><a href="<?= Url::to(['camps', 'type' => Camps::TYPE_REGION, 'alias' => LocRegions::getAliasById($region_id)]) ?>"><?= $region_name ?></a></h3>
                                        <ul>
                                            <? foreach (Camps::find()->byCountry($country_id)->byRegion($region_id)->active()->ordering()->all() AS $camp): ?>
                                                <li><a href="<?= $camp->getCampUrl() ?>" target="_blank"><?= $camp->about->name_short ?></a></li>
                                            <? endforeach; ?>
                                        </ul>
                                    </li>
                                <? endforeach; ?>
                            </ul>
                        <? endif; ?>
                    <? endforeach; ?>
                </li>
        
                <li>
                    <? foreach (LocCountries::getFilterListWithCamps() AS $country_id => $country_name): ?>
                        <? if ($country_id != LocCountries::DEFAULT_ID): ?>
                            <h2><a href="<?= Url::to(['camps', 'type' => Camps::TYPE_COUNTRY, 'alias' => LocCountries::getAliasById($country_id)]) ?>"><?= $country_name ?></a></h2>
                            <ul>
                                <? foreach (Camps::find()->byCountry($country_id)->active()->ordering()->all() AS $camp): ?>
                                    <li><a href="<?= $camp->getCampUrl() ?>" target="_blank"><?= $camp->about->name_short ?></a></li>
                                <? endforeach; ?>
                            </ul>
                        <? endif; ?>
                    <? endforeach; ?>
                </li>
            </ul>
        </div>
        <div class="page-filters">
            <?= \app\widgets\CampRecommend::widget() ?>
            <?= CampSearch::widget(['type' => CampSearch::TYPE_COLUMN]) ?>
        </div>
    </div>
</div>