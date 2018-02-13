<?php
use app\helpers\MetaHelper;
use yii\widgets\ActiveForm;
use app\models\Pages;
use yii\helpers\Url;
use app\models\forms\UploadForm;
use yii\helpers\Html;
use app\helpers\Normalize;
use app\models\Reviews;
use app\models\Orders;
use app\models\BaseItems;
use app\models\Icons;
use app\helpers\CampHelper;
use app\helpers\Statuses;

$purify = new HTMLPurifier();

\app\assets\ColorBoxAsset::register($this);

/* @var $this yii\web\View */
/* @var $model \app\models\Camps */
/* @var $order \app\models\Orders */
/* @var $profile \app\models\Users */

$this->title = $model->about->name_short;
$this->params['meta_t'] = $model->about->name_short;
$this->params['meta_k'] = $model->about->name_variants;
$this->params['meta_d'] = preg_replace('/<\/?(.*)>/i', '', $model->about->name_details);

MetaHelper::setMeta($model, $this);

$this->params['breadcrumbs'] = [
    ['label' => 'Все лагеря', 'url' => ['/camps']],
    ['label' => $model->about->country->name, 'url' => CampHelper::getCountryCampsUrl($model)],
    ['label' => $model->about->region->name, 'url' => CampHelper::getRegionCampsUrl($model)],
];

$types = $model->getTagsTypes();
if ($types) {
    foreach ($types AS $t => $v) {
        $this->params['breadcrumbs'][] = ['label' => $v, 'url' => CampHelper::getTypeCampsUrl($t)];
        break;
    }
}

$this->params['breadcrumbs'][] = ['label' => $model->getAgesText(), 'url' => CampHelper::getAgesCampsUrl($model)];
$this->params['breadcrumbs'][] = $model->about->name_short;

$photos = $model->getPhotos();
$profile = \app\models\Users::getProfile();

/** @var $reviews Reviews[] */
$reviews = Reviews::find()->byCamp($model->id)->active()->ordering()->limit(20)->all();
$reviews_count = Reviews::find()->byCamp($model->id)->active()->count();
?>
<div class="layout-container camp-container">
    <div class="camp-content">
        <div class="row">
            <div class="col-xs-12 col-md-5 col-lg-4">
                <a class="camp-media-main photos-gallery" rel="gallery" href="<?= UploadForm::getSrc($model->media->photo_main) ?>">
                    <img src="<?= UploadForm::getSrc($model->media->photo_main, UploadForm::TYPE_CAMP, '_md') ?>" alt="<?= Html::encode($model->about->name_full) ?>"/>
                    <i class="fa fa-search-plus"></i>
                    <span class="camp-labels-cont">
                        <? if ($model->is_new): ?><span class="camp-labels camp-label-new"
                                                        title="Новый лагерь"
                                                        style="background-image: url('<?= Icons::getIconPath(Icons::ICON_NEW) ?>')"></span><? endif; ?>
                        <? if ($model->is_vip): ?><span class="camp-labels camp-label-vip"
                                                        title="VIP лагерь"
                                                        style="background-image: url('<?= Icons::getIconPath(Icons::ICON_VIP) ?>')"></span><? endif; ?>
                        <? if ($model->is_leader): ?><span class="camp-labels camp-label-leader"
                                                           title="Лидер продаж"
                                                           style="background-image: url('<?= Icons::getIconPath(Icons::ICON_LEADER) ?>')"></span><? endif; ?>
                    </span>
                </a>
            </div>
            <div class="col-xs-12 col-md-7 col-lg-8 m-t-md-20">
                <? if (\app\models\Users::isAdmin()): ?>
                    <div class="pull-right">
                        <a href="<?= Url::to(['/manage/camps/edit', 'id' => $model->id]) ?>" class="btn btn-info" target="_blank">
                            <i class="fa fa-pencil"></i>
                        </a>
                    </div>
                <? endif; ?>
                <div class="row">
                    <div class="col-xs-10">
                        <h1 class="camp-title"><?= Html::encode($model->about->name_short) ?></h1>
                        <h3 class="camp-subtitle">
                            <?= $model->about->country->name ?> / <?= $model->about->region->name ?> / <?= $model->about->loc_address ?>
                            <a class="dis-b m-t-5" href="<?= Url::to(['/camp-map', 'id' => $model->id]) ?>" onclick="show_yandex_map(event, this)">
                                Показать на карте
                            </a>
                        </h3>
                    </div>
                    <? if ($model->stars): ?>
                        <div class="col-xs-2 text-right p-r-7 hidden-xs">
                            <span class="camp-block-points-text"><?= $model->getReviewsTotalText() ?></span>
                        </div>
                    <? endif; ?>
                </div>
    
                <? if ($model->about->tags_services_f): ?>
                    <div class="m-t-20">
                        <ul class="camp-comforts">
                            <? foreach ($model->about->tags_services_f AS $service_id): ?>
                                <? $service_model = \app\models\ComfortTypes::findOne($service_id); ?>
                                <? if (!$service_model) continue; ?>
                                <li>
                                    <a href="<?= CampHelper::getServiceCampsUrl($service_id) ?>" title="<?= Html::encode($service_model->title) ?>">
                                        <img src="/img/comfort/<?= $service_model->icon ?>" alt="" width="36" />
                                    </a>
                                </li>
                            <? endforeach; ?>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                <? endif; ?>
    
                <? if ($model->stars): ?>
                    <a href="<?= Url::to(['/camp-points', 'id' => $model->id]) ?>" onclick="show_camp_points(event, this)" class="camp-points"><?= number_format($model->stars, 1, ',', '') ?></a>
                <? endif; ?>
            </div>
        </div>
    
        <div class="m-t-15">
            <ul class="camp-block-types">
                <? if ($model->contract->opt_gos_compensation): ?>
                    <li><a class="special" href="<?= CampHelper::getTypeCompensationUrl() ?>">Государственная компенсация</a></li>
                <? endif; ?>
                <? if ($model->contract->opt_group_use): ?>
                    <li><a href="<?= CampHelper::getTypeGroupsUrl() ?>">Групповая скидка</a></li>
                <? endif; ?>
            </ul>
        </div>
        
        <div class="m-t-15 hidden visible-xs">
            <div class="camp-order">
                <?
                $form_order = ActiveForm::begin();
                echo Html::activeHiddenInput($order, 'children_count', ['value' => 1]);
        
                /** @var $current_model BaseItems */
                $total_places = $model->itemsActive ? $model->itemsActive[0]->partner_amount : 0;
                $current_price = $model->itemsActive ? $model->itemsActive[0]->getCurrentPrice() : 0;
                $help_block = Html::tag('div', '', ['class' => 'help-block small m-none']);
        
                $order_birth_field = Html::activeTextInput($order, 'child_birth[0]', [
                    'placeholder'  => $order->getAttributeLabel('child_birth'),
                    'class'        => 'form-control datepickers',
                    'autocomplete' => 'off',
                ]);
                $order_fio_field = Html::activeTextInput($order, 'child_fio[0]', [
                    'placeholder'  => $order->getAttributeLabel('child_fio'),
                    'class'       => 'form-control',
                ]);
                ?>
        
                <? if ($model->itemsActive): ?>
                    <span class="camp-order-places">Осталось: <?= Normalize::wordAmount($total_places, ['мест','место','места'], true) ?></span>
                    <span class="camp-order-price">
                        <?= number_format($current_price, 0, '', ' ') ?>&nbsp;<small><?= Html::tag('i', 'p', ['class' => 'als-rub']) ?></small>
                    </span>
            
                    <? if ($model->about->trans_in_price): ?>
                        <span class="camp-order-escort">Дорога включена в стоимость путевки</span>
                    <? endif; ?>
            
            
                    <div class="camp-order-row m-t-15">
                        <? if ($model->itemsActive): ?>
                            <select name="<?= Html::getInputName($order, 'item_id') ?>" id="<?= Html::getInputId($order, 'item_id') ?>"
                                    class="form-control input-lg custom-select"
                                    onchange="change_order_base_item(this, '.camp-order-price')">
                                <? foreach ($model->itemsActive AS $item): ?>
                                    <option value="<?= $item->id ?>"
                                            data-price="<?= number_format($item->getCurrentPrice(), 0, '', ' ') ?>"
                                            data-currency="<?= $item->currency ?>">
                                        <?= '[' . date('d.m', strtotime($item->date_from)) . ' - ' . date('d.m', strtotime($item->date_to)) . '] '
                                        . $item->name_short . ' [' . Normalize::wordAmount($item->partner_amount, ['мест','место','места'], true) . ']'; ?>
                                    </option>
                                <? endforeach; ?>
                            </select>
                        <? else: ?>
                        <? endif; ?>
                    </div>
            
                    <div class="camp-order-block m-t-20 p-b-10 hidden">
                        <div class="alert camp-order-status alert-small hidden"></div>
                        <div class="camp-order-row m-b-10"><?= $order_fio_field ?></div>
                        <div class="camp-order-row m-b-10"><?= $order_birth_field ?></div>
                
                        <div id="order_add_row"></div>
                        <div class="m-b-10">
                            <a class="btn btn-block btn-link btn-sm" href="<?= Url::to(['/' . Pages::getAliasById(Pages::PAGE_ORDER), 'id' => $model->id]) ?>">
                                + поедут несколько детей
                            </a>
                        </div>
                
                        <div class="camp-order-row m-b-10">
                            <?= Html::activeTextInput($order, 'client_fio', [
                                'placeholder' => $order->getAttributeLabel('client_fio'),
                                'class' => 'form-control',
                                'value' => $profile ? $profile->getFullName() : '',
                            ]) ?>
                        </div>
                        <div class="camp-order-row m-b-10">
                            <?= Html::activeTextInput($order, 'client_email', [
                                'placeholder' => $order->getAttributeLabel('client_email'),
                                'class' => 'form-control',
                                'value' => $profile ? $profile->email : '',
                            ]) ?>
                        </div>
                        <div class="camp-order-row">
                            <?= Html::activeTextInput($order, 'client_phone', [
                                'placeholder' => $order->getAttributeLabel('client_phone'),
                                'class' => 'form-control',
                                'value' => $profile ? $profile->phone : '',
                            ]) ?>
                        </div>
                    </div>
            
            
                    <div class="camp-order-use m-t-15">
                        <button class="btn btn-block btn-lg btn-primary btn-border btn-order-send hidden">Отправить заявку</button>
                        <button type="button" class="btn btn-block btn-lg btn-success btn-border btn-order-use">Забронировать</button>
                    </div>
            
                    <div class="alert camp-order-success alert-small m-t-20 hidden"></div>
            
                    <div class="m-t-30">
                        <span class="camp-order-free">Бронирование <strong>БЕСПЛАТНО</strong></span>
                        <span class="camp-order-manager">Менеджер лагеря свяжется с вами<br>в течение 24 часов</span>
                    </div>
                <? else: ?>
                    <span class="camp-order-empty">Нет доступных путевок</span>
                <? endif; ?>
        
                <? ActiveForm::end(); ?>
            </div>
        </div>
        
        <div class="camp-about">
            <ul class="camp-about-tabs">
                <li class="active">
                    <a class="camp-tabs-item camp-tab-about" data-tab="1" href="#about">О лагере</a>
                </li>
                <li><a class="camp-tabs-item camp-tab-program" data-tab="2" href="#program">Программа</a></li>
                <li><a class="camp-tabs-item camp-tab-payment" data-tab="3" href="#payment">Стоимость<span class="hidden-xs hidden-sm"> и оплата</span></a></li>
                <li class="hidden-xs hidden-sm"><a class="camp-tabs-item camp-tab-photos" data-tab="4" href="#photos">Все фото</a></li>
                <li class="hidden-lg hidden-md"><a class="camp-tabs-item camp-tab-photos" data-tab="4" href="#photos">Фото</a></li>
                <? if (!empty($model->media->videos_f)): ?>
                    <li class="hidden-xs hidden-sm"><a class="camp-tabs-item camp-tab-videos" data-tab="7" href="#videos">Видео о нас</a></li>
                <? endif; ?>
                <li><a class="camp-tabs-item camp-tab-reviews" data-tab="5" href="#reviews">Отзывы</a></li>
                <? if ($model->about->isForGroups()): ?>
                    <li><a class="camp-tabs-item camp-tab-groups" data-tab="6" href="#groups">Для групп</a></li>
                <? endif; ?>
            </ul>
            <div class="camp-about-content">
                <div class="camp-about-item" id="camp_about_1">
                    <ul class="camp-block-types">
                        <li><a href="<?= CampHelper::getAgesCampsUrl($model) ?>"><?= $model->getAgesText() ?></a></li>
                        <? foreach ($model->getTagsTypes() AS $t => $v): ?>
                            <li><a href="<?= CampHelper::getTypeCampsUrl($t) ?>"><?= $v ?></a></li>
                        <? endforeach; ?>
                    </ul>
                    
                    <div class="m-t-10 m-b-10">
                        <?= nl2br($purify->purify($model->placement->placement_details)) ?>
                    </div>
                    
                    <table>
                        <tr>
                            <th>Количество мест в лагере</th>
                            <td><?= $model->about->count_places ?></td>
                        </tr>
                        <? if ($model->about->made_year): ?>
                        <tr>
                            <th>Год начала деятельности</th>
                            <td><?= $model->about->made_year ?></td>
                        </tr>
                        <? endif; ?>
                    </table>
                    
                    <table>
                        <tr>
                            <td colspan="2">
                                <table>
                                    <? if (!$model->placement->is_without_places): ?>
                                    <tr>
                                        <td>
                                            <? $first_img = reset($model->media->photos_room_f); ?>
                                            <a href="<?= UploadForm::getSrc($first_img) ?>" class="a-slider max-height-90 ov-h flex-center border-radius-4">
                                                <img width="100%" src="<?= UploadForm::getSrc($first_img, UploadForm::TYPE_CAMP, '_sm') ?>" alt="Где живем" />
                                            </a>
                                        </td>
                                        <td>
                                            <strong>Где живем</strong>
                                            <? if (!empty($model->placements)): ?>
                                                <? foreach ($model->placements AS $pv): ?>
                                                    <p class="m-none m-t-5">
                                                        Размещение в <strong><?= Html::encode($pv->comfort_about) ?> местных номерах</strong>
                                                        с удобствами <strong><?= mb_strtolower($model->placement->getPlacementName($pv->comfort_type), Yii::$app->charset) ?></strong>
                                                    </p>
                                                <? endforeach; ?>
                                            <? endif; ?>
                                        </td>
                                    </tr>
                                    <? endif; ?>
                                    
                                    <? if (!empty($model->media->photos_eating_f)): ?>
                                    <tr>
                                        <td>
                                            <? $first_img = reset($model->media->photos_eating_f); ?>
                                            <a href="<?= UploadForm::getSrc($first_img) ?>" class="a-slider max-height-90 ov-h flex-center border-radius-4">
                                                <img width="100%" src="<?= UploadForm::getSrc($first_img, UploadForm::TYPE_CAMP, '_sm') ?>" alt="Где едим" />
                                            </a>
                                        </td>
                                        <td>
                                            <strong>Где едим</strong>
                                            <p class="m-t-5">
                                                Питание: <?= $model->placement->placement_count_eat ?> - разовое
                                            </p>
                                        </td>
                                    </tr>
                                    <? endif; ?>
                                    
                                    <? if ($model->about->area): ?>
                                    <tr>
                                        <td>
                                            <? $first_img = reset($model->media->photos_area_f); ?>
                                            <a href="<?= UploadForm::getSrc($first_img) ?>" class="a-slider max-height-90 ov-h flex-center border-radius-4">
                                                <img width="100%" src="<?= UploadForm::getSrc($first_img, UploadForm::TYPE_CAMP, '_sm') ?>" alt="Территория" />
                                            </a>
                                        </td>
                                        <td>
                                            <strong>Наша территория</strong>
                                            <p class="m-t-5">
                                                Площадь территории: <?= $model->about->area ?> Га
                                            </p>
                                        </td>
                                    </tr>
                                    <? endif; ?>
                                    
                                    <tr>
                                        <td width="150">
                                            <? $first_img = reset($model->media->photos_sport_f); ?>
                                            <a href="<?= UploadForm::getSrc($first_img) ?>" class="a-slider max-height-90 ov-h flex-center">
                                                <img width="100%" src="<?= UploadForm::getSrc($first_img, UploadForm::TYPE_CAMP, '_sm') ?>" alt="Инфраструктура" />
                                            </a>
                                        </td>
                                        <td>
                                            <strong>Инфраструктура</strong>
                                            <p class="m-t-5">
                                                <?
                                                if ($model->about->count_builds) {
                                                    echo Normalize::wordAmount($model->about->count_builds, ['корпусов','корпус','корпуса'], true) . ', ';
                                                }
                                                echo implode(', ', $model->getTagsPlaces());
                                                ?>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <table>
                        <? if ($model->placement->placement_med || $model->placement->placement_security): ?>
                        <tr>
                            <th>Охрана и медобслуживание</th>
                            <td>
                                <?= nl2br($purify->purify($model->placement->placement_med)) ?>
                                <div class="m-t-5"><?= nl2br($purify->purify($model->placement->placement_security)) ?></div>
                            </td>
                        </tr>
                        <? endif; ?>
                        
                        <? if ($model->client->info_bags): ?>
                            <tr>
                                <th>Что положить в чемодан</th>
                                <td><?= nl2br($purify->purify($model->client->info_bags)) ?></td>
                            </tr>
                        <? endif; ?>
                        
                        <? if ($model->about->trans_escort_cities_f): ?>
                        <tr>
                            <th>Сопровождение из</th>
                            <td>
                                <?= implode(', ', $model->getEscortCities()) ?>
                                <? if ($model->about->trans_in_price): ?>
                                    <p class="m-none m-t-5"><a href="<?= Pages::getUrlById(Pages::PAGE_FREE_TRANS) ?>" class="colorbox-page">Транспортировка входит в стоимость</a></p>
                                <? endif; ?>
                                <? if ($model->about->trans_with_escort): ?>
                                    <p class="m-none m-t-5"><a href="<?= Pages::getUrlById(Pages::PAGE_WITH_ESCORT) ?>" class="colorbox-page">Транспортировка с сопровождением</a></p>
                                <? endif; ?>
                            </td>
                        </tr>
                        <? endif; ?>
                        
                        <? if ($model->client->info_common): ?>
                            <tr>
                                <th>Общая информация</th>
                                <td><?= nl2br($purify->purify($model->client->info_common)) ?></td>
                            </tr>
                        <? endif; ?>
    
                        <? if ($model->about->name_org): ?>
                            <tr>
                                <th>Организатор лагеря</th>
                                <td><?= $model->about->name_org ?></td>
                            </tr>
                        <? endif; ?>
    
                        <? if ($model->client->info_docs): ?>
                            <tr>
                                <th>Документы в лагерь</th>
                                <td><?= nl2br($purify->purify($model->client->info_docs)) ?></td>
                            </tr>
                        <? endif; ?>
                    </table>
                </div>
                <div class="camp-about-item hidden" id="camp_about_2">
                    <div><?= nl2br($purify->purify($model->placement->placement_program)) ?></div>
                    <div class="m-t-5"><?= nl2br($purify->purify($model->placement->placement_regime_day)) ?></div>
                    <div class="m-t-5"><?= nl2br($purify->purify($model->placement->placement_regime_tour)) ?></div>
                </div>
                <div class="camp-about-item hidden" id="camp_about_3">
                    <? if ($model->client->info_common): ?>
                        <div class="m-b-5"><?= nl2br($purify->purify($model->client->info_common)) ?></div>
                    <? endif; ?>
                    <table>
                        <tr>
                            <th>В стоимость входит</th>
                            <td><?= nl2br($purify->purify($model->client->info_payment)) ?></td>
                        </tr>
                        <? if ($model->client->info_dops): ?>
                            <tr>
                                <th>В стоимость не входит</th>
                                <td><?= nl2br($purify->purify($model->client->info_dops)) ?></td>
                            </tr>
                        <? endif; ?>
    
                        <? if ($model->contract->opt_gos_compensation): ?>
                        <tr>
                            <th>Государственная компенсация</th>
                            <td>
                                <ul class="camp-block-types" style="margin: 0">
                                    <li style="margin: 0"><a class="special colorbox-page" href="<?= Pages::getUrlById(Pages::PAGE_COMPENSATION) ?>">ЕСТЬ</a></li>
                                </ul>
                            </td>
                        </tr>
                        <? endif; ?>
                        <tr>
                            <th>Виза</th>
                            <td><?= $model->client->getVisaName() ?></td>
                        </tr>
                    </table>
                </div>
                <div class="camp-about-item hidden" id="camp_about_4">
                    <ul class="camp-media-list">
                        <? $kk = 2; ?>
                        <? foreach ($model->getPhotos() AS $k => $photos_arr): ?>
                            <? if (empty($photos_arr)) continue; ?>
                            <? if (!is_array($photos_arr)) $photos_arr = [$photos_arr]; ?>
                            <? foreach ($photos_arr AS $photo): ?>
                            <li>
                                <a href="<?= UploadForm::getSrc($photo) ?>" rel="gallery" class="photos-gallery">
                                    <img src="<?= UploadForm::getSrc($photo, UploadForm::TYPE_CAMP, '_sm') ?>"
                                         alt="<?= Html::encode($model->about->name_full) ?> - фото <?= ($kk++) ?>" />
                                </a>
                            </li>
                            <? endforeach; ?>
                        <? endforeach; ?>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="camp-about-item hidden" id="camp_about_7">
                    <? if ($model->media->videos_f): ?>
                        <div class="row">
                        <? foreach ($model->media->videos_f AS $video): ?>
                            <?
                            $video_src = Normalize::getVideoSrc($video);
                            if ($video_src):
                            ?>
                            <div class="col-xs-12 col-md-6 m-b-30">
                                <iframe src="<?= Html::encode($video_src) ?>?controls=2&amp;showinfo=0&amp;modestbranding=1&amp;rel=0"
                                        width="100%" height="300" frameborder="0" allowfullscreen></iframe>
                            </div>
                            <? endif; ?>
                        <? endforeach; ?>
                        </div>
                    <? endif; ?>
                </div>
                <div class="camp-about-item hidden" id="camp_about_5">
                    <div class="camp-reviews">
                        <div class="pull-right">
                            <a href="<?= Pages::getUrlById(Pages::PAGE_REVIEW_ADD, ['id' => $model->id]) ?>" class="btn btn-info btn-block">Оставить отзыв</a>
                        </div>
                        <? foreach ($reviews AS $r): ?>
                            <div class="camp-review-item">
                                <span class="review-date"><?= Normalize::getFullDateByTime($r->created) ?></span>
                                <span class="review-name">
                                    <?= Normalize::getStarsIcons($r->stars); ?>
                                    <?= Html::encode($r->user_name) ?>
                                </span>
                                <p class="review-text review-text-positive">
                                    <span class="review-type">Преимущества</span>
                                    <?= Html::encode($r->comment_positive) ?>
                                </p>
                                <? if ($r->comment_negative): ?>
                                <p class="review-text review-text-negative">
                                    <span class="review-type">Недостатки</span>
                                    <?= Html::encode($r->comment_negative) ?>
                                </p>
                                <? endif; ?>
                                <? if ($r->comment_manager): ?>
                                    <p class="review-text review-text-manager">
                                        <span class="review-type">Комментарий менеджера</span>
                                        <?= nl2br(Html::encode($r->comment_manager)) ?>
                                    </p>
                                <? endif; ?>
                            </div>
                        <? endforeach; ?>
                    </div>
                </div>
                <div class="camp-about-item hidden" id="camp_about_6">
                    <div class="camp-groups"><?= nl2br($purify->purify($model->placement->placement_groups)) ?></div>
                    <table>
                        <tr>
                            <th>Минимальное кол-во детей</th>
                            <td><?= $model->contract->opt_group_count ?></td>
                        </tr>
                        <tr>
                            <th>Групповая скидка</th>
                            <td><?= $model->contract->opt_group_discount ?> %</td>
                        </tr>
                        <tr>
                            <th>Количество сопровождающих</th>
                            <td><?= $model->contract->opt_group_guides ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="camp-order camp-order-lg hidden-xs">
        <?
        $form_order2 = ActiveForm::begin();
        echo Html::activeHiddenInput($order, 'children_count', ['value' => 1]);
        
        /** @var $current_model BaseItems */
        $total_places = $model->itemsActive ? $model->itemsActive[0]->partner_amount : 0;
        $current_price = $model->itemsActive ? $model->itemsActive[0]->getCurrentPrice() : 0;
        $help_block = Html::tag('div', '', ['class' => 'help-block small m-none']);
        
        $order_birth_field = Html::activeTextInput($order, 'child_birth[0]', [
            'placeholder'  => $order->getAttributeLabel('child_birth'),
            'class'        => 'form-control datepickers',
            'autocomplete' => 'off',
        ]);
        $order_fio_field = Html::activeTextInput($order, 'child_fio[0]', [
            'placeholder'  => $order->getAttributeLabel('child_fio'),
            'class'       => 'form-control',
        ]);
        ?>
    
        <? if ($model->status == Statuses::STATUS_ACTIVE && $model->itemsActive): ?>
            <span class="camp-order-places">Осталось: <?= Normalize::wordAmount($total_places, ['мест','место','места'], true) ?></span>
            <span class="camp-order-price">
                <span class="camp-order-price-sum"><?= number_format($current_price, 0, '', ' ') ?></span>
                <small><?= Html::tag('i', 'p', ['class' => 'als-rub']) ?></small>
            </span>
            
            <? if ($model->about->trans_in_price): ?>
                <span class="camp-order-escort">Дорога включена в стоимость путевки</span>
            <? endif; ?>
        

            <div class="camp-order-row m-t-15">
                <? if ($model->itemsActive): ?>
                    <select name="<?= Html::getInputName($order, 'item_id') ?>"
                            id="<?= Html::getInputId($order, 'item_id') ?>"
                            data-type="items"
                            class="form-control input-lg custom-select select2"
                            onchange="change_order_base_item(this, '.camp-order-price-sum')">
                        <? foreach ($model->itemsActive AS $item): ?>
                            <option value="<?= $item->id ?>" data-price="<?= number_format($item->getCurrentPrice(), 0, '', ' ') ?>">
                                <?= '[' . date('d.m', strtotime($item->date_from)) . ' - ' . date('d.m', strtotime($item->date_to)) . '] '
                                . $item->name_short . ' [' . Normalize::wordAmount($item->partner_amount, ['мест','место','места'], true) . ']'; ?>
                            </option>
                        <? endforeach; ?>
                    </select>
                <? else: ?>
                <? endif; ?>
            </div>
            
            <div class="camp-order-block m-t-20 p-b-10 hidden">
                <div class="alert camp-order-status alert-small hidden"></div>
                <div class="camp-order-row m-b-10"><?= $order_fio_field ?></div>
                <div class="camp-order-row m-b-10"><?= $order_birth_field ?></div>
            
                <div id="order_add_row"></div>
                <div class="m-b-10">
                    <a class="btn btn-block btn-link btn-sm" href="<?= Url::to(['/' . Pages::getAliasById(Pages::PAGE_ORDER), 'id' => $model->id]) ?>">
                        + поедут несколько детей
                    </a>
                </div>
        
                <div class="camp-order-row m-b-10">
                    <?= Html::activeTextInput($order, 'client_fio', [
                        'placeholder' => $order->getAttributeLabel('client_fio'),
                        'class' => 'form-control',
                        'value' => $profile ? $profile->getFullName() : '',
                    ]) ?>
                </div>
                <div class="camp-order-row m-b-10">
                    <?= Html::activeTextInput($order, 'client_email', [
                        'placeholder' => $order->getAttributeLabel('client_email'),
                        'class' => 'form-control',
                        'value' => $profile ? $profile->email : '',
                    ]) ?>
                </div>
                <div class="camp-order-row">
                    <?= Html::activeTextInput($order, 'client_phone', [
                        'placeholder' => $order->getAttributeLabel('client_phone'),
                        'class' => 'form-control',
                        'value' => $profile ? $profile->phone : '',
                    ]) ?>
                </div>
            </div>
        
    
            <div class="camp-order-use m-t-15">
                <button class="btn btn-block btn-lg btn-order-send hidden">Отправить заявку</button>
                <button type="button" class="btn btn-block btn-lg btn-order-use">Забронировать</button>
            </div>

            <div class="alert camp-order-success alert-small m-t-20 hidden"></div>
    
            <div class="m-t-30">
                <span class="camp-order-free">Бронирование <strong>БЕСПЛАТНО</strong></span>
                <span class="camp-order-manager">Менеджер лагеря свяжется с вами<br>в течение 24 часов</span>
            </div>
        <? else: ?>
            <span class="camp-order-empty">Нет доступных путевок</span>
        <? endif; ?>
        
        <? ActiveForm::end(); ?>
    </div>
</div>

<div class="layout-container">
    <div class="m-t-75">
        <?= \app\widgets\CampOrders::widget() ?>
    </div>
</div>

<?php
$this->registerCss("
    .select2-container--default .select2-selection--single {
        height: 48px;
        border-radius: 6px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 48px;
    }
    .select2-container .select2-selection--single .select2-selection__rendered {
        line-height: 48px;
    }
");
$this->registerJs("
// открываем поля для оформления заявки
$('.btn-order-use').on('click', function(){
    $('.btn-order-use, .btn-order-send').toggleClass('hidden');
    $('.camp-order-block').hide().removeClass('hidden').slideDown();
    setTimeout(function(){
        $('#" . Html::getInputId($order, 'child_fio[0]') . "').focus();
    }, 500);
});

// отправка заявки
$('#{$form_order->id},#{$form_order2->id}').on('beforeSubmit', function(){
    var \$f = $(this).closest('form');
    var \$m = $('.camp-order-status');
    var \$s = $('.camp-order-success');
    
    $.ajax({
        url: '" . Url::to(['/ajax/order']) . "',
        data: \$f.serialize(),
        beforeSend: function(){
            \$m.removeClass('hidden alert-danger alert-success').hide().html('');
            \$s.removeClass('hidden alert-danger alert-success').hide().html('');
            loader.show(\$f);
        },
        success: function(resp){
        
            if (resp.errors) {
                for (var k in resp.errors) {
                    \$m.append(resp.errors[k].join('<br/>') + '<br />');
                }
                \$m.addClass('alert-danger').show();
            } else {
                \$s.addClass('alert-success').html(resp.message).show();
                if (resp.button) \$s.append(resp.button);
            }
            
            $('html,body').stop().animate({
                scrollTop: ($('.breadcrumb').offset().top - 60)
            });
        }
    });
    
    return false;
});

$('.photos-gallery').colorbox({
    rel: 'gallery',
    speed: 0,
    title: '" . Html::encode($model->about->name_short) . "',
    maxWidth: '80%',
    maxHeight: '90%'
});

$('.camp-tabs-item').on('click', function(){
    $(this).parent().siblings().removeClass('active');
    $(this).parent().addClass('active');
    
    var d = $(this).data('tab');
    $('.camp-about-item').addClass('hidden');
    $('#camp_about_' + d).removeClass('hidden');
    
    location.hash = $(this).attr('href').replace('#','');
});

var hash = location.hash.replace('#','');
if (hash) {
    if ($('.camp-tab-' + hash).length) {
        $('.camp-tab-' + hash).trigger('click');
    } else if (hash == 'order') {
        $('.btn-order-use').trigger('click');
    }
}
");
