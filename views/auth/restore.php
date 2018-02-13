<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\forms\RestoreForm */

$this->title = 'Восстановление пароля';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="layout-container">
    <h1 class="index-title"><?= Html::encode($this->title) ?></h1>
    
    <? if (Yii::$app->session->hasFlash('success')): ?>
        <div class="m-t-20 m-b-40"><?= \app\helpers\MHtml::alertMsg(); ?></div>
    
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
    <? else: ?>
        <div class="row m-t-20 m-b-50">
            <div class="col-xs-12 col-md-offset-4 col-md-4">
                <?php $form = ActiveForm::begin([
                    'fieldConfig' => [
                        'template' => '<div class="col-xs-12">{input}{error}</div>'
                    ],
                ]); ?>
        
                <?= $form->field($model, 'name')->textInput([
                    'autofocus' => true,
                    'placeholder' => $model->getAttributeLabel('name')
                ]) ?>
                <?= $form->field($model, 'email')->textInput([
                    'placeholder' => $model->getAttributeLabel('email')
                ]) ?>
                
                <div class="h-10"></div>
                
                <div class="form-group">
                    <div class="col-xs-12">
                        <?= Html::submitButton('Восстановить пароль', ['class' => 'btn btn-primary btn-block']) ?>
                    </div>
                </div>
        
                <div class="h-10"></div>
        
                <div class="form-group">
                    <div class="col-xs-12 col-md-offset-3 col-md-6">
                        <a href="<?= Url::to(['/auth/login']) ?>" class="btn btn-link btn-block">Личный кабинет</a>
                    </div>
                </div>
                
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    <? endif; ?>
</div>