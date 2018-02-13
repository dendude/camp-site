<?php
use app\helpers\MetaHelper;
use app\widgets\CampSearch;
use app\models\Camps;
use app\models\forms\UploadForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\models\Pages */

$this->title = $model->title;
$this->params['id'] = $model->id;

MetaHelper::setMeta($model, $this);
?>
<div class="layout-container p-t-20">
    <? if ($model->isSearchFilter()): ?>
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
    <? else: ?>
        <h1 class="index-title m-t-0"><?= $model->title ?></h1>
        <article><?= $model->content ?></article>
    <? endif; ?>
</div>