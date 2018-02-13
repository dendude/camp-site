<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\forms\LoginForm */

$this->title = 'Вход в личный кабинет';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="layout-container">
    <h1 class="index-title"><?= Html::encode($this->title) ?></h1>
    
    <div class="row m-t-20 m-b-50">
        <div class="col-xs-12 col-md-offset-4 col-md-4">
            <?php $form = ActiveForm::begin([
                'fieldConfig' => [
                    'template' => '<div class="col-xs-12">{input}{error}</div>'
                ],
            ]); ?>
        
            <?= $form->field($model, 'email')->textInput([
                'autofocus' => true,
                'placeholder' => $model->getAttributeLabel('email')
            ]) ?>
            <?= $form->field($model, 'password')->passwordInput([
                'placeholder' => $model->getAttributeLabel('password')
            ]) ?>
            
            <div class="h-10"></div>
            
            <div class="form-group">
                <div class="col-xs-12">
                    <?= Html::submitButton('Войти', ['class' => 'btn btn-primary btn-block']) ?>
                </div>
            </div>
    
            <div class="h-10"></div>
    
            <div class="form-group">
                <div class="col-xs-12 col-md-offset-3 col-md-6">
                    <a href="<?= Url::to(['/auth/restore']) ?>" class="btn btn-link btn-block">Забыли пароль?</a>
                </div>
            </div>
            
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>