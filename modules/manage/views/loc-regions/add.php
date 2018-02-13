<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\manage\controllers\LocCountriesController;
use app\modules\manage\controllers\LocRegionsController;
use \app\models\LocRegions;
use yii\helpers\Url;

/** @var $model LocRegions */
/** @var $this \yii\web\View */

$action = $model->id ? 'Редактирование региона' : 'Добавление региона';
$this->title = $action;
$this->params['breadcrumbs'] = [
    ['label' => LocCountriesController::LIST_NAME, 'url' => ['loc-countries/list']],
    ['label' => LocRegionsController::LIST_NAME, 'url' => ['list']],
    ['label' => $action]
];

$w100 = ['inputOptions' => ['class' => 'form-control w-100']];
$w200 = ['inputOptions' => ['class' => 'form-control w-200']];
$w300 = ['inputOptions' => ['class' => 'form-control w-300']];

$form = ActiveForm::begin();
?>
<div class="max-width-1000">
    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
    <? \app\helpers\MHtml::alertMsg(); ?>
    <div class="box box-primary">
        <div class="box-header with-border"><?= Yii::$app->params['required_fields'] ?></div>
        <div class="box-body">
            <?= $form->field($model, 'country_id', $w300)->dropDownList(\app\models\LocCountries::getFilterList(), ['prompt' => '']) ?>
    
            <div class="separator"></div>
            
            <?= $form->field($model, 'name') ?>
            <?= $form->field($model, 'name_in')->textInput(['placeholder' => 'Пример: в Крыму']) ?>
            
            <?= \app\helpers\MHtml::aliasField($model, 'alias', 'alias') ?>
            <div class="row row-comment">
                <div class="col-xs-offset-4 col-xs-8">
                    Пример: вводим "стадион", клик "Получить URL" покажет "stadion".<br/>
                    После сохранения ссылка на страницу будет такой: "stadion.html".
                </div>
            </div>
    
            <div class="separator"></div>
            <?= $form->field($model, 'meta_t')->textarea(['onkeyup' => 'charsCalculate(this)', 'maxlength' => true]) ?>
            <?= $form->field($model, 'meta_d')->textarea(['onkeyup' => 'charsCalculate(this)', 'maxlength' => true]) ?>
            <?= $form->field($model, 'meta_k')->textarea(['onkeyup' => 'charsCalculate(this)', 'maxlength' => true]) ?>
            <div class="separator"></div>
                
            <div class="separator"></div>
            <?= $form->field($model, 'content', ['template' => '<div class="col-xs-4 text-right">{label}</div><div class="col-xs-8">{error}</div>']) ?>
            <?= \app\modules\manage\widgets\FroalaEditorWidget::widget([
                'model' => $model,
                'field' => 'content',
        
                'imageUploadUrl' => Url::to(['upload-image']),
                'filesUploadUrl' => Url::to(['upload-files']),
            ]); ?>
            <div class="separator"></div>
            
            <?= $form->field($model, 'ordering', $w100)->input('number') ?>
    
            <div class="separator"></div>
    
            <?= $form->field($model, 'status')->checkbox(['class' => 'ichecks', 'label' => 'Опубликовать на сайте']) ?>
            
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