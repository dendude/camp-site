<?
use yii\helpers\Html;
use app\helpers\Normalize;
use app\models\forms\UploadForm;
use yii\helpers\Url;
use app\models\Pages;
use app\models\Icons;
use app\helpers\CampHelper;

\app\assets\ColorBoxAsset::register($this);

/**
 * @var $m \app\models\Camps
 * @var $base_items \app\models\BaseItems[]
 */

$purify = new HTMLPurifier();
?>
<div class="camp-item">
    <div class="row">
        <div class="col-xs-12 col-sm-4 col-lg-3">
            <a href="<?= $m->getCampUrl() ?>" class="camp-block-url" title="<?= Html::encode($m->about->name_short) ?>">
                <span class="camp-block-image">
                    <img src="<?= UploadForm::getSrc($m->media->photo_main, UploadForm::TYPE_CAMP, '_md') ?>"
                         alt="<?= Html::encode($m->about->name_short) ?>" />
                </span>
            </a>
            <? if ($m->min_price): ?>
                <span class="camp-item-price">
                    <?= number_format($m->min_price, 0, '', ' ') ?>&nbsp;<i class="als-rub">p</i>
                </span>
            <? endif; ?>
        </div>
        <div class="col-xs-12 col-sm-8 col-lg-9">
            <a class="camp-block-camp-name" href="<?= $m->getCampUrl() ?>">
                <?= Html::encode($m->about->name_short) ?>
            </a>
            <a class="camp-block-location"><?= Html::encode($m->about->country->name . '/' . $m->about->region->name) ?></a>
            <p class="camp-block-about"><?= Html::encode(strip_tags($m->about->name_details)) ?></p>
            <div class="row m-t-15">
                <div class="col-xs-12 col-md-8">
                    <? if ($m->about->tags_services_f): ?>
                        <ul class="camp-comforts">
                            <? foreach ($m->about->tags_services_f AS $service_id): ?>
                                <? $service_model = \app\models\ComfortTypes::findOne($service_id); ?>
                                <? if (!$service_model) continue; ?>
                                <li>
                                    <a href="<?= CampHelper::getServiceCampsUrl($service_id) ?>" title="<?= Html::encode($service_model->title) ?>">
                                        <img src="/img/comfort/<?= $service_model->icon ?>" alt="" width="36" />
                                    </a>
                                </li>
                            <? endforeach; ?>
                        </ul>
                    <? endif; ?>
                </div>
                <div class="col-xs-12 col-md-4 text-right hidden-xs hidden-sm">
                    <a href="<?= $m->getCampUrl() ?>#order" class="camp-block-order">Забронировать</a>
                </div>
                <div class="col-xs-12 col-md-4 text-left m-t-15 hidden visible-xs visible-sm">
                    <a href="<?= $m->getCampUrl() ?>#order" class="camp-block-order">Забронировать</a>
                </div>
            </div>
        </div>
    </div>
</div>