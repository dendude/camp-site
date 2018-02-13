<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception \yii\web\HttpException */

$message = rtrim($message, '.');
$code = $exception->statusCode ? $exception->statusCode : $exception->getCode();

$this->title = $message;
?>
<div class="layout-container">
    <h1 class="page-title">Ошибка <?= $code ?></h1>
    <div class="alert alert-danger"><?= nl2br(Html::encode($message)) ?></div>
    
    <div class="m-t-50">
        <?= \app\widgets\CampOrders::widget() ?>
    </div>
</div>