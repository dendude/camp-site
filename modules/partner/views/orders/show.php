<?
use app\models\Orders;
use yii\helpers\Html;
use app\helpers\Normalize;
use app\helpers\Statuses;

/** @var $model Orders */

$this->title = "Бронь № {$model->id}";
?>
<h1 class="text-center"><?= $this->title ?></h1>
<table class="table table-condensed table-bordered table-striped">
    <tr>
        <th colspan="2" class="text-center">Путёвка</th>
    </tr>
    <tr>
        <td class="text-right" width="50%">Место отдыха</td>
        <th class="text-left"><?= Html::encode($model->camp->about->country->name . ', ' . $model->camp->about->region->name) ?></th>
    </tr>
    <tr>
        <td class="text-right" width="50%">Лагерь</td>
        <th class="text-left"><?= Html::encode($model->camp->about->name_short) ?></th>
    </tr>
    <tr>
        <td class="text-right">Смена</td>
        <th class="text-left"><?= Html::encode($model->campItem->name_full) ?></th>
    </tr>
    <tr>
        <td class="text-right">Дата заезда</td>
        <th class="text-left"><?= Normalize::getShortDate($model->campItem->date_from) ?> - <?= Normalize::getShortDate($model->campItem->date_to) ?></th>
    </tr>
    <tr>
        <td class="text-right">Цена</td>
        <th class="text-left"><?= $model->price_user . ' ' . $model->currency ?><? if ($model->camp->about->trans_in_price): ?> [Транспортировка входит в стоимость]<? endif; ?></th>
    </tr>
    <tr>
        <td class="text-right">Статус</td>
        <th class="text-left"><?= Statuses::getFull($model->status, Statuses::TYPE_ORDER) ?></th>
    </tr>
    <tr>
        <th colspan="2" class="text-center">Заказчик</th>
    </tr>
    <tr>
        <td class="text-right">Фамилия Имя Отчество</td>
        <th class="text-left"><?= Html::encode($model->order_data['client_fio']) ?></th>
    </tr>
    <tr>
        <td class="text-right">Email-адрес</td>
        <th class="text-left"><?= Html::encode($model->order_data['client_email']) ?></th>
    </tr>
    <? if ($model->order_data['client_phone']): ?>
    <tr>
        <td class="text-right">Телефон</td>
        <th class="text-left"><?= Normalize::formatPhone($model->order_data['client_phone']) ?></th>
    </tr>
    <? endif; ?>
    <? if ($model->order_data['client_comment']): ?>
        <tr>
            <td class="text-right">Комментарий</td>
            <th class="text-left"><?= Html::encode($model->order_data['client_comment']) ?></th>
        </tr>
    <? endif; ?>
    
    <? if ($model->order_data['children_count'] > 1): ?>
        <tr>
            <th colspan="2" class="text-center">Дети</th>
        </tr>
        <tr>
            <td class="text-right">Количество</td>
            <th class="text-left"><?= Html::encode($model->order_data['children_count']) ?></th>
        </tr>
        <? foreach ($model->order_data['child_fio'] AS $k => $v): ?>
            <tr><td colspan="2"></td></tr>
            <tr>
                <td class="text-right">ФИО ребенка <?= ($k + 1) ?></td>
                <th class="text-left"><?= Html::encode($model->order_data['child_fio'][$k]) ?></th>
            </tr>
            <tr>
                <td class="text-right">Дата рождения ребенка <?= ($k + 1) ?></td>
                <th class="text-left"><?= Normalize::getDate($model->order_data['child_birth'][$k]) ?></th>
            </tr>
        <? endforeach; ?>
    <? else: ?>
        <tr>
            <th colspan="2" class="text-center">Ребенок</th>
        </tr>
        <tr>
            <td class="text-right">Фамилия Имя Отчество</td>
            <th class="text-left"><?= Html::encode($model->order_data['child_fio'][0]) ?></th>
        </tr>
        <tr>
            <td class="text-right">Дата рождения</td>
            <th class="text-left"><?= Normalize::getDate($model->order_data['child_birth'][0]) ?></th>
        </tr>
    <? endif; ?>
</table>
