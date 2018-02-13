<?php
/**
 * @var $model \app\models\search\OrdersSearch
 * @var $list Orders[]
 * @var $totalSum float
 */
use app\models\Orders;
use app\helpers\Normalize;
use yii\helpers\Html;
use app\models\CampsContract;
use app\helpers\Statuses;
use yii\helpers\Json;
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="/img/favicon-192x192.jpg" type="image/x-icon" />
    
    <title>Отчет о клиентах</title>
</head>
<body>
<? if (!$list): ?>
    <h3>Записи не найдены</h3>
<? else: ?>
    <h2 align="center">Отчет о клиентах № <?= date('Ymd') ?></h2>
    <p align="center">
        от «<?= date('d', strtotime($model->date_from)) ?>» <?= Normalize::getMonthFullName($model->date_from) ?> <?= date('Y', strtotime($model->date_from)) ?> г.
        до «<?= date('d', strtotime($model->date_to)) ?>» <?= Normalize::getMonthFullName($model->date_to) ?> <?= date('Y', strtotime($model->date_to)) ?> г.
    </p>
    <p>Исполнитель Общество с ограниченной ответственностью «<?= Yii::$app->params['company_name'] ?>»</p>
    <p>Заказчик <?= Html::encode($list[0]->camp->about->name_full) ?></p>
    
    <table width="100%" cellpadding="5" cellspacing="0" border="1" style="font-size: 11px;">
        <tr>
            <th>№ п/п</th>
            <th>№ заявки</th>
            <th>Лагерь</th>
            <th>Смена</th>
            <th>ФИО заказчика</th>
            <th>Цена от партнера</th>
            <th>Цена покупателя</th>
            <th>Комиссия</th>
            <th>Статус</th>
        </tr>
    <? foreach ($list AS $k => $item): ?>
        <? $order_data = Json::decode($item->details); ?>
        <tr>
            <td align="center"><?= ($k + 1) ?></td>
            <td align="center"><?= $item->id ?></td>
            <td align="left"><?= $item->camp->about->name_short ?></td>
            <td align="left"><?= $item->campItem->name_full ?></td>
            <td align="center"><?= Html::encode($order_data['client_fio']) ?></td>
            <td align="center"><?= $item->campItem->partner_price ?>&nbsp;<?= $item->campItem->currency ?></td>
            <td align="center"><?= $item->campItem->getCurrentPrice() ?>&nbsp;<?= Orders::CUR_RUB ?></td>
            <td align="center"><?= $item->campItem->comission_value ?>&nbsp;<?= $item->campItem->comission_type == CampsContract::COMISSION_SUM ? Orders::CUR_RUB : '%' ?></td>
            <td align="center"><?= Statuses::getFull($item->status, Statuses::TYPE_ORDER) ?></td>
        </tr>
    <? endforeach; ?>
    </table>
    
    <p><strong>Итого</strong></p>
    <p>
        Всего оказано услуг на сумму:
        <strong><?= array_shift(explode('.', $totalSum)) ?></strong> рублей
        <strong><?= array_pop(explode('.', $totalSum)) ?></strong> копеек
    </p>
    
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top: 30px;">
        <tr>
            <td align="left">Исполнитель <?= str_repeat('_', 30) ?></td>
            <td align="right">Заказчик <?= str_repeat('_', 30) ?></td>
        </tr>
        <tr><td colspan="2">&nbsp;</td></tr>
        <tr>
            <td align="left">Краснослободцев А.В.</td>
            <td align="right"><?= str_repeat('_', 40) ?></td>
        </tr>
        <tr><td colspan="2" height="50">&nbsp;</td></tr>
        <tr>
            <td align="left">М.П.</td>
            <td align="right">М.П.</td>
        </tr>
    </table>
<? endif; ?>
</body>
</html>
