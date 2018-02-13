<?
use yii\helpers\Url;
use app\models\Reviews;
use app\helpers\Normalize;
use app\models\Orders;

$this->title = 'Личный кабинет';

$count_orders = Orders::find()->bySelf()->using()->count();
$count_reviews = Reviews::find()->bySelf()->using()->count();
?>
<div class="clearfix"></div>
<div class="row">
    <div class="col-xs-12 col-md-6">
        <a href="<?= Url::to(['orders/list']) ?>" class="btn-office">
            <?= Normalize::wordAmount($count_orders, ['бронирований','бронь','бронирования'], true) ?>
        </a>
    </div>
    <div class="col-xs-12 col-md-6">
        <a href="<?= Url::to(['reviews/list']) ?>" class="btn-office">
            <?= Normalize::wordAmount($count_reviews, ['отзывов','отзыв','отзыва'], true) ?>
        </a>
    </div>
</div>