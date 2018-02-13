<?
use app\models\Menu;
use yii\helpers\Html;
use app\models\Pages;
use yii\helpers\Url;
use app\models\Users;
use app\models\TagsTypes;
use app\models\Camps;

$settings = \app\models\Settings::lastSettings();
$header_menu = Menu::find()->active()->top()->all();
$header_submenu = Menu::find()->active()->subtop()->all();
$contacts_url = Pages::getUrlById(Pages::PAGE_CONTACTS_ID);
?>
<header class="header">
    <div class="header-menu">
        <div class="layout-container pos-r">
            <? if ($header_menu): ?>
                <nav class="header-nav">
                    <ul class="header-nav-slider">
                        <li class="header-nav__logo">
                            <a href="<?= Yii::$app->homeUrl ?>" class="header-logo" title="На главную">
                                <img src="/img/logo.png" alt="<?= Yii::$app->name ?>" />
                            </a>
                        </li>
                        <li class="header-nav__call">
                            <a href="<?= $contacts_url ?>" class="header-phone-call hidden-xs">8-800-222-74-66</a>
                            <a href="tel:88002227466" class="header-phone-call hidden visible-xs">8-800-222-74-66</a>
                            <a href="#" class="header-call">перезвоните мне</a>
                        </li>
                        <li class="header-nav__menu">
                            <ul class="header-nav__subnav">
                                <? foreach ($header_menu AS $item): ?>
                                    <li <? if (isset($this->params['id']) && $this->params['id'] == $item->page_id): ?>class="active"<? endif; ?>>
                                        <a href="<?= Pages::getUrlById($item->page_id) ?>"><?= Html::encode($item->name) ?></a>
                                    </li>
                                <? endforeach; ?>
                                <? if (Yii::$app->user->isGuest): ?>
                                    <li><a href="<?= Url::to(['/auth/login']) ?>"><i class="fa fa-sign-in"></i>Вход</a></li>
                                <? else: ?>
                                    <li><a href="<?= Url::to(['/office/main/index']) ?>">Личный кабинет</a></li>
                                <? endif; ?>
                                <? if (Users::isAdmin()): ?>
                                    <li><a href="<?= Url::to(['/manage/main/index']) ?>">Admin</a></li>
                                <? endif; ?>
                                <? if (!Yii::$app->user->isGuest): ?>
                                    <li><a href="<?= Url::to(['/auth/logout']) ?>">Выход</a></li>
                                <? endif; ?>
                            </ul>
                        </li>
                    </ul>
                    <button class="header-nav-bar hidden visible-xs" onclick="slide_menu('.header-nav-slider-menu')">
                        <i class="fa fa-bars"></i>
                    </button>
                </nav>
                <div class="hidden visible-xs">
                    <ul class="header-nav-slider-menu">
                        <? foreach ($header_menu AS $item): ?>
                            <? if ($item->page_id): ?>
                                <li <? if (isset($this->params['id']) && $this->params['id'] == $item->page_id): ?>class="active"<? endif; ?>>
                                    <a href="<?= Pages::getUrlById($item->page_id) ?>"><?= Html::encode($item->name) ?></a>
                                </li>
                            <? else: ?>
                                <li <? if (isset($this->params['type_id']) && $this->params['type_id'] == $item->type_id): ?>class="active"<? endif; ?>>
                                    <a href="<?= $item->type ? $item->type->getUrl() : '#' ?>"><?= Html::encode($item->name) ?></a>
                                </li>
                            <? endif; ?>
                        <? endforeach; ?>
                        <? if ($header_submenu): ?>
                            <? foreach ($header_submenu AS $item): ?>
                                <? if ($item->page_id): ?>
                                    <li><a href="<?= Pages::getUrlById($item->page_id) ?>"><?= Html::encode($item->name) ?></a></li>
                                <? else: ?>
                                    <li><a href="<?= $item->type ? $item->type->getUrl() : '#' ?>"><?= Html::encode($item->name) ?></a></li>
                                <? endif; ?>
                            <? endforeach; ?>
                        <? endif; ?>
                    </ul>
                </div>
            <? endif; ?>
        </div>
    </div>
    
    <? if ($header_submenu): ?>
    <div class="header-submenu bg-white hidden-xs">
        <div class="layout-container">
            <nav class="header-nav">
                <ul class="header-nav-submenu">
                    <? foreach ($header_submenu AS $item): ?>
                        <? if ($item->page_id): ?>
                            <li <? if ($item->page_id == Pages::PAGE_BONUSES_ID): ?>class="active"<? endif; ?>>
                                <a href="<?= Pages::getUrlById($item->page_id) ?>"><?= Html::encode($item->name) ?></a>
                            </li>
                        <? else: ?>
                            <li><a href="<?= $item->type ? $item->type->getUrl() : '#' ?>"><?= Html::encode($item->name) ?></a></li>
                        <? endif; ?>
                    <? endforeach; ?>
                </ul>
            </nav>
        </div>
    </div>
    <? endif; ?>
</header>