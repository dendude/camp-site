<?
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\models\BaseItems;
use app\helpers\Statuses;

/**
 * @var $model \app\models\Orders
 * @var $this \yii\web\View
 */

$this->title = "Редактирование заявки № {$model->id}";
$this->params['breadcrumbs'] = [
    ['label' => \app\modules\partner\controllers\OrdersController::LIST_NAME, 'url' => ['index']],
    $this->title
];

$w150 = ['inputOptions' => ['class' => 'form-control w-150']];
$w300 = ['inputOptions' => ['class' => 'form-control w-300']];
?>
<div class="clearfix"></div>

<? $form = ActiveForm::begin(); ?>

<?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
<?= \app\helpers\MHtml::alertMsg(); ?>

<div class="bg-gray p-25 b-r-6">
    
    <div class="form-group">
        <div class="col-xs-12 col-md-offset-4 col-md-8">
            <h2 class="order-form-about">Сведения о путёвке</h2>
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-xs-12 col-md-offset-4 col-md-8">
            <span class="required">*</span> - поля, обязательные для заполнения
        </div>
    </div>
    
    <? if ($model->camp->items): ?>
        <?= $form->field($model, 'item_id')->dropDownList(BaseItems::getFilterListOrder($model->camp->id))->label('Смена') ?>
        
        <div class="p-t-15"></div>
        <?= $form->field($model, 'children_count', [
            'template' => '<div class="col-xs-12 col-lg-4 text-right">{label}</div>
                           <div class="col-xs-12 col-lg-8">
                               <div class="row">
                                   <div class="col-xs-3">{input}</div>
                                   <div class="col-xs-9"><p class="form-control-static">' . $group_text . '</p></div>
                               </div>{error}
                           </div>'
        ])->textInput([
            'type' => 'number', 'min' => 1, 'step' => 1, 'max' => 100,
            'value' => ($model->children_count ? $model->children_count : 1),
            'class' => 'form-control',
            'onchange' => 'change_price(this)'
        ]) ?>
    <? endif; ?>
    
    <div class="form-group">
        <div class="col-xs-12 col-md-4 text-right">
            <label for="" class="control-label">Стоимость</label>
        </div>
        <div class="col-xs-12 col-md-8">
            <p class="form-control-static">
                <strong class="text-success" id="result_price"><?= $model->price_user ?></strong> руб
            </p>
        </div>
    </div>
    
    <div class="children-rows">
        <? foreach ($model->order_data['child_fio'] AS $k => $v): ?>
            <div class="child-row">
                <div class="p-t-15"></div>
                <?= $form->field($model, "child_fio[{$k}]")->textInput([
                    'value' => $model->order_data['child_fio'][$k]
                ]) ?>
                <?= $form->field($model, "child_birth[{$k}]")->textInput([
                    'value' => $model->order_data['child_birth'][$k],
                    'class' => 'form-control w-150 datepickers'
                ]) ?>
            </div>
        <? endforeach; ?>
    </div>
    
    <div class="p-t-15"></div>
    
    <?= $form->field($model, 'client_fio')->textInput(['value' => $model->order_data['client_fio'], 'placeholder' => 'Иванов Иван Иванович']) ?>
    <?= $form->field($model, 'client_email')->textInput(['value' => $model->order_data['client_email'], 'placeholder' => 'example@mail.ru']) ?>
    <?= $form->field($model, 'client_phone')->textInput(['value' => $model->order_data['client_phone'], 'placeholder' => '+7 123 4567890']) ?>
    <?= $form->field($model, 'client_comment')->textarea(['value' => $model->order_data['client_comment'], 'placeholder' => 'Дополнительный контактный номер телефона, пожелания, вопросы']) ?>
    
    <div class="p-t-15"></div>
    <?= $form->field($model, 'status')->dropDownList(Statuses::statuses(Statuses::TYPE_ORDER)) ?>
    <?= $form->field($model, 'price_payed', $w150) ?>
    <div class="p-t-15"></div>
    
    <div class="form-group">
        <div class="col-xs-12 col-md-offset-4 col-md-8">
            <button type="submit" class="btn btn-primary">Сохранить заявку</button>
        </div>
    </div>
</div>

<?php ActiveForm::end() ?>

<div id="tmp_child" class="hidden">
    <div class="child-row">
        <div class="p-t-15"></div>
        <?= $form->field($model, 'child_fio[{i}]') ?>
        <?= $form->field($model, 'child_birth[{i}]')->textInput(['class' => 'form-control w-150 datepickers']) ?>
    </div>
</div>

<script>
    var prices = [];
    <? foreach ($model->camp->items AS $item): ?>prices[<?= $item->id ?>] = <?= $item->getCurrentPrice(); ?>; <? endforeach; ?>
    
    function change_price(obj) {
        var count = $(obj).val();
        var $price = $('#result_price');
        var $items = $('#<?= Html::getInputId($model, 'item_id') ?>');
        
        if (count >= 1) {
            $price.text( prices[$items.val()] * parseInt(count) );
            
            $('.children-rows .child-row').each(function(k,v){
                // удаляем лишние
                if (k >= count) $(this).remove();
            });
            
            for (var i = 0; i < count; i++) {
                
                // добавляем недостающие
                if ($('.children-rows .child-row').eq(i).length == 0) {
                    $('.children-rows').append($('#tmp_child').html().replace(/{i}/g, i));
                    set_datepickers($('#orders-child_birth-' + i));
                }
                
                $('#orders-child_fio-' + i).attr('placeholder', (i + 1) + ' ребенок');
            }
        }
    }
</script>