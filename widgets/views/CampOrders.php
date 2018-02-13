<?php
use app\models\forms\UploadForm;
use app\helpers\Normalize;
use yii\helpers\Html;
use app\helpers\Statuses;

/** @var $orders \app\models\Orders[] */
?>
<div class="camp-new-orders">
    <h2 class="index-title">Недавно забронированные</h2>
    <div class="slider-container">
        <ul class="slider-orders">
            <? foreach ($orders AS $k => $m): ?>
                <li>
                    <a href="<?= $m->camp->getCampUrl() ?>" class="new-orders__item">
                        <span class="new-orders__photo">
                            <img src="<?= UploadForm::getSrc($m->camp->media->photo_main, UploadForm::TYPE_CAMP, '_md') ?>"
                                 alt="<?= Html::encode($m->camp->about->name_short) ?>" />
                        </span>
                        <span class="new-orders__info">
                            <span class="new-orders__title"><?= Html::encode($m->camp->about->name_short) ?></span>
                            <span class="new-orders__status status-<?= $m->status == Statuses::STATUS_PAYED ? '1' : '0' ?>">
                                <?= $m->status == Statuses::STATUS_PAYED ? 'Продано' : 'Забронировано' ?>
                            </span>
                        </span>
                    </a>
                </li>
            <? endforeach; ?>
        </ul>
        <div class="slider-controls slider-controls-orders">
            <span class="slide-prev"></span>
            <span class="slide-next"></span>
        </div>
    </div>
</div>
<?php
$this->registerJs("
tns({
    container: document.querySelector('.slider-orders'),
    controlsContainer: document.querySelector('.slider-controls-orders'),    
    autoplayHoverPause: true,
    controls: true,
    autoplay: true,
    nav: false,
    speed: 750,
    items: 3,
    responsive: {
        400: 1,
        600: 2,
        1100: 3
    }
});
");