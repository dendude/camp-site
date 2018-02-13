<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Users;
use yii\helpers\Url;
use \app\models\forms\UploadForm;

$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="layout-container">
    <h1 class="index-title"><?= Html::encode($this->title) ?></h1>
    
    <div class="m-t-20 m-b-40 alert alert-<?= $class ?>"><?= $message ?></div>
    
    <div class="row">
        <div class="col-xs-12 col-md-offset-3 col-md-6">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <a href="<?= Url::to(['login']) ?>" class="btn btn-block btn-primary">Вход в личный кабинет</a>
                </div>
                <div class="col-xs-12 col-md-6">
                    <a href="<?= Url::to(['register']) ?>" class="btn btn-block btn-link">Регистрация на сайте</a>
                </div>
            </div>
        </div>
    </div>
</div>