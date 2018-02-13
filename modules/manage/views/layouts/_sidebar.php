<?php
use \yii\helpers\Url;
use app\models\forms\UploadForm;
use app\models\Users;
use yii\helpers\Html;

$controller_id = Yii::$app->controller->id;
$action_id = Yii::$app->controller->action->id;
$ca = "{$controller_id}/$action_id";

$actives = [$controller_id => 'class="active"'];
$ca_actives = [$ca => 'class="active"'];

// счетчики
$new_camps = \app\models\Camps::find()->waiting()->count();
$new_orders = \app\models\Orders::find()->waiting()->count();
$new_changes = \app\models\Changes::find()->waiting()->count();
//$new_questions = \app\models\Faq::find()->waiting()->count();
$new_reviews = \app\models\Reviews::find()->waiting()->count();

/** Users */
$user = Users::$profile;
?>
<aside class="main-sidebar">
    <section class="sidebar">

        <div class="user-panel">
            <div class="pull-left image">
                <? if ($user->photo): ?>
                    <img src="<?= UploadForm::getSrc($user->photo, UploadForm::TYPE_PROFILE, '_sm') ?>" class="img-circle" alt="">
                <? else: ?>
                    <img src="/lib/AdminLTE/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                <? endif; ?>
            </div>
            <div class="pull-left info">
                <p><?= Html::encode(Yii::$app->user->identity->first_name) ?></p>
                <span style="font-size: 12px"><i class="fa fa-circle text-success"></i>&nbsp;&nbsp;<?= $user->getRoleName() ?></span>
            </div>
        </div>

        <ul class="sidebar-menu">
            
            <li class="header">Менеджер</li>
            
            <li <?= @$actives['default'] ?>>
                <a href="<?= Url::to(['main/index']) ?>">
                    <i class="fa fa-home"></i>&nbsp;<span>Главная</span>
                </a>
            </li>
        
            <li <?= @$actives['reviews'] ?>>
                <a href="<?= Url::to(['reviews/list']) ?>">
                    <i class="fa fa-comments"></i>&nbsp;<span>Отзывы</span>
                    <? if ($new_reviews): ?>&nbsp;<small class="label bg-red">+<?= $new_reviews ?></small><? endif; ?>
                </a>
            </li>
    
            <li <?= @$ca_actives['camps/changed'] ?>>
                <a href="<?= Url::to(['camps/changed']) ?>">
                    <i class="fa fa-exchange"></i>&nbsp;<span>Изменения лагерей</span>
                    <? if ($new_changes): ?>&nbsp;<small class="label bg-orange">+<?= $new_changes ?></small><? endif; ?>
                </a>
            </li>
    
            <li <?= @$actives['orders'] ?>>
                <a href="<?= Url::to(['orders/list']) ?>">
                    <i class="fa fa-ticket"></i>&nbsp;<span>Бронирования</span>
                    <? if ($new_orders): ?>&nbsp;<small class="label bg-blue">+<?= $new_orders ?></small><? endif; ?>
                </a>
            </li>
                
            <? $is_active = in_array($controller_id, ['camps-new','camps','camps-ratings','camps-icons','camps-types','camps-places','camps-sports','camps-selections'])
                            && !@$ca_actives['camps/changed']; ?>
            
            <li class="treeview <?= $is_active ? 'active' : '' ?>">
                <a href="#">
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left"></i>
                    </span>
                    <i class="fa fa-star"></i>&nbsp;<span>База лагерей</span>
                    <? if ($new_camps): ?>&nbsp;<small class="label bg-green">+<?= $new_camps ?></small><? endif; ?>
                </a>
                <ul class="treeview-menu">
                    <li <?= @$actives['camps-new'] ?>>
                        <a href="<?= Url::to(['camps-new/list']) ?>">
                            <? if ($new_camps): ?>
                                <i class="fa fa-circle text-green"></i>&nbsp;<span class="text-green">Новые заявки</span>
                            <? else: ?>
                                <i class="fa fa-circle-o"></i>&nbsp;<span>Новые заявки</span>
                            <? endif; ?>
                        </a>
                    </li>
                    <li <?= @$actives['camps'] ?>>
                        <a href="<?= Url::to(['camps/list']) ?>">
                            <i class="fa fa-circle-o"></i>&nbsp;<span>Список лагерей</span>
                        </a>
                    </li>
                    <li <?= @$actives['camps-selections'] ?>>
                        <a href="<?= Url::to(['camps-selections/list']) ?>">
                            <i class="fa fa-circle-o"></i>&nbsp;<span>Подборки</span>
                        </a>
                    </li>
                    <li <?= @$actives['camps-icons'] ?>>
                        <a href="<?= Url::to(['camps-icons/list']) ?>">
                            <i class="fa fa-circle-o"></i>&nbsp;<span>Иконки для лагерей</span>
                        </a>
                    </li>
    
                    <li <?= @$actives['camps-types'] ?>>
                        <a href="<?= Url::to(['camps-types/list']) ?>">
                            <i class="fa fa-circle-o"></i>&nbsp;<span>Типы лагерей</span>
                        </a>
                    </li>
                    <li <?= @$actives['camps-places'] ?>>
                        <a href="<?= Url::to(['camps-places/list']) ?>">
                            <i class="fa fa-circle-o"></i>&nbsp;<span>Инфраструктура</span>
                        </a>
                    </li>
                    <li <?= @$actives['camps-sports'] ?>>
                        <a href="<?= Url::to(['camps-sports/list']) ?>">
                            <i class="fa fa-circle-o"></i>&nbsp;<span>Виды спорта</span>
                        </a>
                    </li>
                    
                </ul>
            </li>
    
            <li class="treeview <?= in_array($controller_id, ['news','pages','bonuses','menu','faq']) ? 'active' : '' ?>">
                <a href="#">
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left"></i>
                    </span>
                    <i class="fa fa-file-text"></i>&nbsp;<span>Содержимое сайта</span>
                </a>
                <ul class="treeview-menu">
                    <li <?= @$actives['news'] ?>>
                        <a href="<?= Url::to(['news/list']) ?>">
                            <i class="fa fa-circle-o"></i>&nbsp;<span>Новости</span>
                        </a>
                    </li>
                    <li <?= @$actives['pages'] ?>>
                        <a href="<?= Url::to(['pages/list']) ?>">
                            <i class="fa fa-circle-o"></i>&nbsp;<span>Страницы</span>
                        </a>
                    </li>
                    <li <?= @$actives['bonuses'] ?>>
                        <a href="<?= Url::to(['bonuses/list']) ?>">
                            <i class="fa fa-circle-o"></i>&nbsp;<span>Бонусы</span>
                        </a>
                    </li>
                    <li <?= @$actives['menu'] ?>>
                        <a href="<?= Url::to(['menu/list']) ?>">
                            <i class="fa fa-circle-o"></i>&nbsp;<span>Меню</span>
                        </a>
                    </li>
                    <li <?= @$actives['faq'] ?>>
                        <a href="<?= Url::to(['faq/list']) ?>">
                            <i class="fa fa-circle-o"></i>&nbsp;<span>Вопросы и ответы</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="header">Администратор</li>
        
            <li <?= @$actives['mail-sends'] ?>>
                <a href="<?= Url::to(['mail-sends/list']) ?>">
                    <i class="fa fa-send"></i>&nbsp;<span>Рассылки</span>
                </a>
            </li>
    
            <li <?= @$actives['stats'] ?>>
                <a href="<?= Url::to(['stats/index']) ?>">
                    <i class="glyphicon glyphicon-stats"></i>&nbsp;<span>Статистика</span>
                </a>
            </li>
    
            <li <?= @$actives['actions'] ?>>
                <a href="<?= Url::to(['actions/list']) ?>">
                    <i class="fa fa-tasks"></i>&nbsp;<span>Журнал действий</span>
                </a>
            </li>
    
            <li class="treeview <?= in_array($controller_id, ['users','subscribers']) ? 'active' : '' ?>">
                <a href="#">
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left"></i>
                    </span>
                    <i class="fa fa-users"></i>&nbsp;<span>Пользователи</span>
                </a>
                <ul class="treeview-menu">
                    <li <?= @$actives['users'] ?>>
                        <a href="<?= Url::to(['users/list']) ?>">
                            <i class="fa fa-circle-o"></i>&nbsp;<span>Список пользователей</span>
                        </a>
                    </li>
                    <li <?= @$actives['subscribers'] ?>>
                        <a href="<?= Url::to(['subscribers/list']) ?>">
                            <i class="fa fa-circle-o"></i>&nbsp;<span>Подписчики</span>
                        </a>
                    </li>
                </ul>
            </li>
    
            <li class="treeview <?= in_array($controller_id, ['loc-countries','loc-regions','loc-cities']) ? 'active' : '' ?>">
                <a href="#">
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left"></i>
                    </span>
                    <i class="fa fa-globe"></i>&nbsp;<span>Геолокация</span>
                </a>
                <ul class="treeview-menu">
                    <li <?= @$actives['loc-countries'] ?>>
                        <a href="<?= Url::to(['loc-countries/list']) ?>">
                            <i class="fa fa-circle-o"></i>&nbsp;<span>Страны</span>
                        </a>
                    </li>
                    <li <?= @$actives['loc-regions'] ?>>
                        <a href="<?= Url::to(['loc-regions/list']) ?>">
                            <i class="fa fa-circle-o"></i>&nbsp;<span>Регионы</span>
                        </a>
                    </li>
                    <li <?= @$actives['loc-cities'] ?>>
                        <a href="<?= Url::to(['loc-cities/list']) ?>">
                            <i class="fa fa-circle-o"></i>&nbsp;<span>Города</span>
                        </a>
                    </li>
                </ul>
            </li>
    
            <li class="treeview <?= in_array($controller_id, ['socials-settings','mail-settings','notifications','mail-templates','payments','reviews-items','comfort-types']) ? 'active' : '' ?>">
                <a href="#">
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left"></i>
                    </span>
                    <i class="fa fa-cogs"></i> <span>Настройки</span>
                </a>
                <ul class="treeview-menu">
                    <li <?= @$actives['limits-settings'] ?>>
                        <a href="<?= Url::to(['limits-settings/index']) ?>">
                            <i class="fa fa-circle-o"></i>&nbsp;<span>Лимиты</span>
                        </a>
                    </li>
                    <li <?= @$actives['payments'] ?>>
                        <a href="<?= Url::to(['payments/index']) ?>">
                            <i class="fa fa-circle-o"></i>&nbsp;<span>Платежи</span>
                        </a>
                    </li>
                    <li <?= @$actives['notifications'] ?>>
                        <a href="<?= Url::to(['notifications/index']) ?>">
                            <i class="fa fa-circle-o"></i>&nbsp;<span>Уведомления</span>
                        </a>
                    </li>
                    <li <?= @$actives['mail-settings'] ?>>
                        <a href="<?= Url::to(['mail-settings/index']) ?>">
                            <i class="fa fa-circle-o"></i>&nbsp;<span>Отправка почты</span>
                        </a>
                    </li>
                    <li <?= @$actives['mail-templates'] ?>>
                        <a href="<?= Url::to(['mail-templates/list']) ?>">
                            <i class="fa fa-circle-o"></i>&nbsp;<span>Шаблоны писем</span>
                        </a>
                    </li>
                    <li <?= @$actives['reviews-items'] ?>>
                        <a href="<?= Url::to(['reviews-items/list']) ?>">
                            <i class="fa fa-circle-o"></i>&nbsp;<span>Критерии отзывов</span>
                        </a>
                    </li>
                    <li <?= @$actives['socials-settings'] ?>>
                        <a href="<?= Url::to(['socials-settings/index']) ?>">
                            <i class="fa fa-circle-o"></i>&nbsp;<span>Социальные сети</span>
                        </a>
                    </li>
                    <li <?= @$actives['comfort-types'] ?>>
                        <a href="<?= Url::to(['comfort-types/list']) ?>">
                            <i class="fa fa-circle-o"></i>&nbsp;<span>Удобства и услуги</span>
                        </a>
                    </li>
                </ul>
            </li>
    
            <li <?= @$actives['sitemap'] ?>>
                <a href="<?= Url::to(['/site/sitemap']) ?>" target="_blank">
                    <i class="fa fa-sitemap"></i>&nbsp;<span>Карта сайта</span>
                </a>
            </li>
            
        </ul>

    </section>
</aside>