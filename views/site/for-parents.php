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
/* @var $search SearchForm */

$this->title = $model->title;
$this->params['id'] = $model->id;

MetaHelper::setMeta($model, $this);
?>
<div class="layout-container m-t-20">
    <div class="page-with-filters">
        <div class="page-with-filters-content">
            <h1 class="page-title m-t-0"><?= $model->title ?></h1>
            <article><?= $model->content ?></article>
        </div>
        <div class="page-filters">
            <?= \app\widgets\CampRecommend::widget() ?>
            <?= CampSearch::widget(['type' => CampSearch::TYPE_COLUMN]) ?>
        </div>
    </div>
</div>