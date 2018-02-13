<?php
use app\helpers\MetaHelper;
use yii\widgets\ActiveForm;
use app\models\Pages;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model \app\models\Pages */
/* @var $search \app\models\forms\SearchForm */

$this->title = $model->title;
MetaHelper::setMeta($model, $this);
?>
<div class="layout-container">
    <h1 class="index-title m-t-0"><?= $model->title ?></h1>
</div>