<?php
use app\helpers\MetaHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\helpers\Normalize;
use app\models\BaseItems;
use app\components\PayTravel;
use yii\helpers\Url;
use app\helpers\CampHelper;

/* @var $this yii\web\View */
/* @var $camp \app\models\Camps */
/* @var $order \app\models\Orders */
/* @var $model \app\models\Pages */

$this->title = $model->title;
$this->params['id'] = $model->id;

MetaHelper::setMeta($model, $this);

$this->params['breadcrumbs'] = [
    ['label' => 'Все лагеря', 'url' => ['/camps']],
    ['label' => $camp->about->country->name, 'url' => CampHelper::getCountryCampsUrl($camp)],
    ['label' => $camp->about->region->name, 'url' => CampHelper::getRegionCampsUrl($camp)],
    ['label' => $camp->getAgesText(), 'url' => CampHelper::getAgesCampsUrl($camp)],
    ['label' => $camp->about->name_short, 'url' => $camp->getCampUrl()],
];

$form_config = [
    'fieldConfig' => [
        'template' => '<div class="col-xs-12 col-sm-5 col-md-4 text-right">{label}</div><div class="col-xs-12 col-sm-7 col-md-8">{input}{error}</div>',
        'labelOptions' => ['class' => 'control-label']
    ]
];

$this->params['breadcrumbs'][] = $model->title;

$base_items = $camp->itemsActive;
$group_text = $camp->contract->opt_group_use
            ? 'групповая скидка от ' . Normalize::wordAmount($camp->contract->opt_group_count, ['человек','человека','человек'], true)
            : '';
?>
<div class="layout-container camp-container">
    <div class="camp-content camp-order-form">
        <h1 class="order-form-title m-t-0">
            Оформление брони на путевку в лагерь:<br>
            <strong><a href="<?= $camp->getCampUrl() ?>"><?= Html::encode($camp->about->name_short) ?></a></strong>
        </h1>
                
        <? if (Yii::$app->session->hasFlash('success') && Yii::$app->session->hasFlash('order_id') ||
               Yii::$app->session->hasFlash('error')): ?>
            
            <?= \app\helpers\MHtml::alertMsg(); ?>
            
            <form action="<?= \yii\helpers\Url::to(['/payment/go']) ?>" method="GET" target="_blank">
                <input type="hidden" name="order_id" value="<?= Yii::$app->session->getFlash('order_id') ?>"/>
                <? if ($camp->contract->opt_use_paytravel): ?>
                    <!--подключена оплата на сайте-->
                    <button class="btn btn-lg btn-success" type="submit">Оплатить заявку</button>
                <? endif; ?>
                <a href="#" class="btn btn-lg btn-link">Оформить ещё одну заявку</a>
            </form>
            
        <? else: ?>
            
            <? $form = ActiveForm::begin($form_config); ?>
                <?= Html::activeHiddenInput($order, 'camp_id', ['value' => $camp->id]) ?>
                
                <div class="form-group">
                    <div class="col-xs-12 col-sm-offset-5 col-sm-7 col-md-offset-4 col-md-8">
                        <h2 class="order-form-about">Сведения о путёвке</h2>
                    </div>
                </div>
        
                <div class="form-group">
                    <div class="col-xs-12 col-sm-offset-5 col-sm-7 col-md-offset-4 col-md-8">
                        <span class="required">*</span> - поля, обязательные для заполнения
                    </div>
                </div>
                
                <? if ($camp->itemsActive): ?>
                    <?= $form->field($order, 'item_id')->dropDownList(BaseItems::getFilterListOrder($camp->id), [
                        'onchange' => 'get_item_price(this)'
                    ])->label('Смена') ?>
                
                    <div class="p-t-15"></div>
                    <?= $form->field($order, 'children_count', [
                        'template' => '<div class="col-xs-12 col-sm-5 col-md-4 text-right">{label}</div>
                                       <div class="col-xs-12 col-sm-7 col-md-8">
                                           <div class="row">
                                               <div class="col-xs-12 text-xs-right col-sm-3">{input}</div>
                                               <div class="col-xs-12 text-xs-right col-sm-9"><p class="form-control-static">' . $group_text . '</p></div>
                                           </div>{error}
                                       </div>'
                    ])->textInput([
                        'type' => 'number', 'min' => 1, 'step' => 1, 'max' => 100,
                        'value' => ($order->children_count ? $order->children_count : 1),
                        'class' => 'form-control',
                        'onchange' => 'change_price(this)'
                    ]) ?>
                <? endif; ?>
                <div class="form-group">
                    <div class="col-xs-12 col-sm-5 col-md-4 text-right">
                        <label for="" class="control-label">Стоимость</label>
                    </div>
                    <div class="col-xs-12 col-sm-7 col-md-8">
                        <p class="form-control-static">
                            <? if ($camp->itemsActive): ?>
                                <strong class="text-success bold" id="result_price">
                                    <?= $camp->itemsActive[0]->getCurrentPrice() ?>
                                </strong>&nbsp;<?= Html::tag('i', 'p', ['class' => 'als-rub']) ?>
                            <? else: ?>
                                <strong class="text-danger">Нет свободных мест</strong>
                            <? endif; ?>
                        </p>
                    </div>
                </div>
                
                <div class="children-rows">
                    <? if (Yii::$app->request->post('Orders')): ?>
                        <? foreach ($order->child_fio AS $k => $v): ?>
                            <div class="child-row">
                                <div class="p-t-15"></div>
                                <?= $form->field($order, "child_fio[{$k}]") ?>
                                <?= $form->field($order, "child_birth[{$k}]")->textInput(['class' => 'form-control w-150 datepickers']) ?>
                            </div>
                        <? endforeach; ?>
                    <? else: ?>
                        <div class="child-row">
                            <div class="p-t-15"></div>
                            <?= $form->field($order, 'child_fio[0]') ?>
                            <?= $form->field($order, 'child_birth[0]')->textInput(['class' => 'form-control w-150 datepickers']) ?>
                        </div>
                    <? endif; ?>
                </div>
        
                <div class="p-t-15"></div>
                    
                <?= $form->field($order, 'client_fio')->textInput(['placeholder' => 'Иванов Иван Иванович']) ?>
                <?= $form->field($order, 'client_email')->textInput(['placeholder' => 'example@mail.ru']) ?>
                <?= $form->field($order, 'client_phone')->textInput(['placeholder' => '+7 123 4567890']) ?>
                <?= $form->field($order, 'client_comment')->textarea(['placeholder' => 'Дополнительный контактный номер телефона, пожелания, вопросы']) ?>
        
                <div class="form-group">
                    <div class="col-xs-12 col-sm-offset-5 col-sm-7 col-md-offset-4 col-md-8">
                        <button type="submit" class="btn btn-primary">Забронировать</button>
                    </div>
                </div>
        
            <? ActiveForm::end(); ?>
    
            <div id="tmp_child" class="hidden">
                <div class="child-row">
                    <div class="p-t-15"></div>
                    <?= $form->field($order, 'child_fio[{i}]') ?>
                    <?= $form->field($order, 'child_birth[{i}]')->textInput(['class' => 'form-control w-150 datepickers']) ?>
                </div>
            </div>
            
        <? endif; ?>
    </div>
    
    <div class="camp-order camp-order-info">
        <h3 class="order-title">Как забронировать?</h3>
        <ol class="order-steps">
            <li>Выберите смену</li>
            <li>
                Выберите количество путевок. Вы сразу можете увидеть их стоимость.
                Цена путёвок в рамках одного заказа может отличаться, в случае если рекламная скидка предоставляется на ограниченное количество путевок.
                Например, если количество путевок по рекламной скидке - 5, то шестая и последующие путевки будут
                предоставляться по более высокой цене - цене <?= Yii::$app->params['company'] ?>.
                Тем не менее, цена <?= Yii::$app->params['company'] ?> будет также дешевле цены лагеря.
            </li>
            <li>Заполните информацию о детях и контактные данные.</li>
            <li>Нажмите кнопку "Забронировать" (бронирование и аннуляция бесплатны).</li>
        </ol>
        <p>После этого в течение 24 часов с вами свяжется менеджер лагеря и уточнит детали вашего заказа.</p>
    </div>
</div>

<div class="layout-container">
    <div class="m-t-75">
        <?= \app\widgets\CampOrders::widget() ?>
    </div>
</div>

<script>
    var prices = [];
    <? foreach ($camp->itemsActive AS $item): ?>prices[<?= $item->id ?>] = <?= $item->getCurrentPrice(); ?>; <? endforeach; ?>
    
    function change_price(obj) {
        var count = $(obj).val();
        var $price = $('#result_price');
        var $items = $('#<?= Html::getInputId($order, 'item_id') ?>');
        
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
    
    function get_item_price(obj) {
        ajaxData(obj, null, '<?= Url::to(['/ajax/item-price']) ?>', {
            'item_id': obj.value
        }, function(resp){
            $('#result_price').text(resp.price);
            change_price('#<?= Html::getInputId($order, 'children_count') ?>');
        });
    }
</script>