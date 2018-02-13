<?
use yii\helpers\Url;
use app\models\Users;

$ca = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
$act[$ca] = 'class="active"';
?>
<nav class="office-menu">
    <ul>
        <li <?= @$act['main/index'] ?>><a href="<?= Url::to(['main/index']) ?>"><i class="fa fa-laptop"></i> Главная</a></li>
        <li <?= @$act['orders/list'] ?>><a href="<?= Url::to(['orders/list']) ?>"><i class="fa fa-suitcase"></i> Мои бронирования</a></li>
        <!--<li <?/*= @$act['reserves/list'] */?>><a href="<?/*= Url::to(['reserves/list']) */?>"><i class="fa fa-ticket"></i> Мои заявки</a></li>-->
        <!--<li <?/*= @$act['bonuses/list'] */?>><a href="<?/*= Url::to(['bonuses/list']) */?>"><i class="fa fa-gift"></i> Мои бонусы</a></li>-->

        <li <?= @$act['reviews/list'] ?>><a href="<?= Url::to(['reviews/list']) ?>"><i class="fa fa-comment-o"></i> Мои отзывы</a></li>
        <!--<li <?/*= @$act['messages/list'] */?>><a href="<?/*= Url::to(['messages/list']) */?>"><i class="fa fa-comments-o"></i> Мои сообщения</a></li>-->
        <li <?= @$act['profile/index'] ?>><a href="<?= Url::to(['profile/index']) ?>"><i class="fa fa-user"></i> Мой профиль</a></li>
        <li <?= @$act['settings/index'] ?>><a href="<?= Url::to(['settings/index']) ?>"><i class="fa fa-cogs"></i> Мои настройки</a></li>
        <!--<li <?/*= @$act['notifications/list'] */?>><a href="<?/*= Url::to(['notifications/list']) */?>"><i class="fa fa-bell-o"></i> Мои уведомления</a></li>-->

        <? if (Users::isPartner()): ?>
            <li>
                <a href="<?= Url::to(['/partner/main/index']) ?>">
                    <span class="text-danger"><i class="fa fa-share"></i> В кабинет партнера</span>
                </a>
            </li>
        <? endif; ?>
    </ul>
</nav>
<?= \app\widgets\OfficeCurses::widget() ?>