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
 * @var $model \app\models\News
 */

$this->title = $model->title;
$this->params['breadcrumbs'] = [
    ['label' => 'Новости', 'url' => Pages::getUrlById(Pages::PAGE_NEWS_ID)],
    $model->title
];

MetaHelper::setMeta($model, $this);
?>
<div class="layout-container">
    <h1 class="index-title m-t-0"><?= $model->title ?></h1>
    <article><?= $model->content ?></article>
</div>