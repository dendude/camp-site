<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var $model \app\models\Users */
/** @var $this \yii\web\View */

$action = $model->id ? 'Редактирование пользователя' : 'Добавление пользователя';
$this->title = $action;
$this->params['breadcrumbs'] = [
    ['label' => \app\modules\manage\controllers\UsersController::LIST_NAME, 'url' => ['list']],
    ['label' => $action]
];

$form = ActiveForm::begin();

$w100 = ['inputOptions' => ['class' => 'form-control w-100']];
$w200 = ['inputOptions' => ['class' => 'form-control w-200']];
?>
<div class="max-width-1000">
    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
    <? \app\helpers\MHtml::alertMsg(); ?>
    <div class="box box-primary">
        <div class="box-header with-border"><?= Yii::$app->params['required_fields'] ?></div>
        <div class="box-body">
            <?= $form->field($model, 'role', $w200)->dropDownList(\app\models\Users::getRoles(), ['prompt' => '']) ?>

            <div class="separator"></div>

            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'pass_origin')->passwordInput([
                'placeholder' => $model->isNewRecord ? '' : 'Оставьте поле пустым для сохранения старого пароля'
            ]) ?>
            
            <div class="separator"></div>

            <?= $form->field($model, 'first_name') ?>
            <?= $form->field($model, 'last_name') ?>
            <?= $form->field($model, 'sur_name') ?>

            <div class="separator"></div>
    
            <?= $form->field($model, 'status')->checkbox(['class' => 'ichecks', 'label' => 'Активировать пользователя']) ?>
            
            <div class="separator"></div>

            <div class="form-group">
                <div class="col-xs-offset-4 col-xs-2">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" value="<?= Yii::$app->request->referrer ?>" name="ref-page"/>
<?php ActiveForm::end() ?>