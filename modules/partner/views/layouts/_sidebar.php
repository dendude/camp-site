<?
use yii\helpers\Url;
use app\models\Users;

$ca = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
$act[$ca] = 'class="active"';
?>
<nav class="office-menu">
    <ul>
        <li <?= @$act['main/index'] ?>><a href="<?= Url::to(['main/index']) ?>"><i class="fa fa-laptop"></i> Главная</a></li>
        <li <?= @$act['camps/list'] ?>><a href="<?= Url::to(['camps/list']) ?>"><i class="fa fa-child"></i> Детские лагеря</a></li>
        <!--<li <?/*= @$act['bases/list'] */?>><a href="<?/*= Url::to(['bases/list']) */?>"><i class="fa fa-group"></i> Базы для групп</a></li>-->
        <li <?= @$act['orders/list'] ?>><a href="<?= Url::to(['orders/list']) ?>"><i class="fa fa-ticket"></i> Заявки на путевки</a></li>
        <li <?= @$act['reviews/list'] ?>><a href="<?= Url::to(['reviews/list']) ?>"><i class="fa fa-comment-o"></i> Отзывы о лагерях</a></li>
        <li <?= @$act['stats/index'] ?>><a href="<?= Url::to(['stats/index']) ?>"><i class="fa fa-line-chart"></i> Статистика</a></li>

        <li <?= @$act['finances/list'] ?>><a href="<?= Url::to(['finances/list']) ?>"><i class="fa fa-rub"></i> Финансы</a></li>
        <li <?= @$act['contract/index'] ?>><a href="<?= Url::to(['contracts/list']) ?>"><i class="fa fa-file-text-o"></i> Договоры</a></li>
        <li <?= @$act['settings/index'] ?>><a href="<?= Url::to(['settings/index']) ?>"><i class="fa fa-sliders"></i> Настройки</a></li>

        <li>
            <a href="<?= Url::to(['/office/main/index']) ?>">
                <span class="text-success"><i class="fa fa-reply"></i> В личный кабинет</span>
            </a>
        </li>
    </ul>
</nav>
<?= \app\widgets\OfficeCurses::widget() ?>