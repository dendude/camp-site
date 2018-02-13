<?
use app\models\Orders;
use app\models\Users;
use app\models\Camps;
use app\models\Reviews;
use app\helpers\Normalize;
use yii\helpers\Url;

$this->title = 'Панель управления';

$today_time = strtotime(date('Y-m-d'));

$new_orders = (int) Orders::find()->waiting()->count();
$new_camps = (int) Camps::find()->waiting()->count();

$new_users = (int) Users::find()->where('created >= :time', [':time' => $today_time])->count();
$new_reviews = (int) Reviews::find()->waiting()->count();
?>
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3><?= $new_orders ?></h3>
                <p><?= Normalize::wordAmount($new_orders, ['Новых бронирований','Новая бронь','Новых бронирования']) ?></p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a class="small-box-footer" href="<?= Url::to(['orders/list']) ?>">
                Подробнее&nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3><?= $new_camps ?></h3>
                <p><?= Normalize::wordAmount($new_camps, ['Новых лагерей','Новый лагерь','Новых лагеря']) ?></p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a class="small-box-footer" href="<?= Url::to(['camps-new/list']) ?>">
                Подробнее&nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <!-- ./col -->
</div>
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3><?= $new_users ?></h3>
                <p>Регистраций за сегодня</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a class="small-box-footer" href="<?= Url::to(['users/list']) ?>">
                Подробнее&nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3><?= $new_reviews ?></h3>
                <p><?= Normalize::wordAmount($new_reviews, ['Новых отзывов','Новый отзыв','Новых отзыва']) ?></p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <a class="small-box-footer" href="<?= Url::to(['reviews/list']) ?>">
                Подробнее&nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <!-- ./col -->
</div>