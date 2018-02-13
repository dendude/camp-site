<?php
use app\models\forms\SearchForm;
use app\helpers\Normalize;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use app\helpers\MetaHelper;
use app\models\Camps;
use app\models\forms\UploadForm;

/* @var $this yii\web\View */
/* @var $search SearchForm */
/* @var $model \app\models\Pages */
/* @var $model_type \yii\base\Object */
/* @var $type string */
/* @var $alias string */
/* @var $models Camps[] */
/* @var $pages \yii\data\Pagination */

$titles_arr = [];

if ($search->type) {
    $type_model = \app\models\TagsTypes::findOne($search->type);
    if ($type_model) $titles_arr[] = $type_model->title_many;
} else {
    $titles_arr[] = 'Лагеря';
}

if ($search->region_id) {
    $region_model = \app\models\LocRegions::findOne($search->region_id);
    if ($region_model) $titles_arr[] = $region_model->name_in;
} elseif ($search->country_id) {
    $country_model = \app\models\LocCountries::findOne($search->country_id);
    if ($country_model) $titles_arr[] = $country_model->name_in;
}

if (empty($titles_arr)) $titles_arr[] = 'Поиск лагерей';

$this->title = implode(' ', $titles_arr);
$this->params['id'] = $model->id;

MetaHelper::setMeta($model_type, $this);

switch ($type) {
    case Camps::TYPE_TYPE:
        /** @var $type_model \app\models\TagsTypes */
        $type_model = \app\models\TagsTypes::find()->where(['alias' => $alias])->one();
        $this->params['type_id'] = $type_model->id;
        break;
    
    case Camps::TYPE_COUNTRY:
        /** @var $type_model \app\models\LocCountries */
        $type_model = \app\models\LocCountries::find()->where(['alias' => $alias])->one();
        break;
    
    case Camps::TYPE_REGION:
        /** @var $type_model \app\models\LocRegions */
        $type_model = \app\models\LocRegions::find()->where(['alias' => $alias])->one();
        break;
        
    default:
        $type_model = null;
}
?>
<div class="layout-container">
    <div class="page-with-filters">
        <div class="page-with-filters-content" style="border: none; padding: 0">
            <? if (isset($type_model)): ?>
                <article><?= $type_model->content; ?></article>
            <? endif; ?>
            <div class="row">
                <div class="col-xs-12 col-md-7">
                    <h2 class="index-subtitle"><?= Html::encode($this->title) ?></h2>
                </div>
                <div class="col-xs-12 col-md-5">
                    <h2 class="index-subtitle text-right hidden-xs hidden-sm">
                        Найдено <strong><?= Normalize::wordAmount($pages->totalCount, ['лагерей','лагерь','лагеря'], true) ?></strong>
                    </h2>
                    <h2 class="index-subtitle text-left hidden visible-xs visible-sm p-t-0 p-b-20">
                        Найдено <strong><?= Normalize::wordAmount($pages->totalCount, ['лагерей','лагерь','лагеря'], true) ?></strong>
                    </h2>
                </div>
            </div>
            <? if ($models): ?>
    
                <div class="row m-b-20">
                    <div class="col-xs-12 col-md-offset-8 col-md-4">
                        <select name="" id="" class="form-control custom-select" onchange="changeSort(this.value, '/<?= Yii::$app->request->pathInfo ?>')">
                            <option value="rating">По рейтингу</option>
                            <option value="price-asc" <?= Yii::$app->request->get('sort') == 'price-asc' ? 'selected' : '' ?>>От дешевых к дорогим</option>
                            <option value="price-desc" <?= Yii::$app->request->get('sort') == 'price-desc' ? 'selected' : '' ?>>От дорогих к дешевым</option>
                        </select>
                    </div>
                </div>
                
                <? foreach ($models AS $m): ?>
                    <?= $this->render('camp-item', ['m' => $m]) ?>
                <? endforeach; ?>
                <div class="text-center m-t-15">
                    <?= LinkPager::widget(['pagination' => $pages]);?>
                </div>
            <? else: ?>
                <div class="alert alert-info m-t-30 m-b-30">Лагеря не найдены</div>
            <? endif; ?>
        </div>
        <div class="page-filters">
            <?= \app\widgets\CampRecommend::widget() ?>
            <?= \app\widgets\CampSearch::widget(['type' => \app\widgets\CampSearch::TYPE_COLUMN]) ?>
        </div>
    </div>
    <?= \app\widgets\CampOrders::widget() ?>
</div>
<script>
    function changeSort(val, path) {
        var href = path + '?sort=' + val;
        
        <? if (Yii::$app->request->get('ages')): ?>href += '&ages=<?= Html::encode(Yii::$app->request->get('ages')) ?>';<? endif; ?>
        <? if (Yii::$app->request->get('date')): ?>href += '&date=<?= Html::encode(Yii::$app->request->get('date')) ?>';<? endif; ?>
        
        location.href = href;
    }
</script>