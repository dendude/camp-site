<?php
use app\helpers\MetaHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\TagsTypes;
use app\models\TagsSport;
use app\models\TagsPlaces;
use app\models\CampsContract;
use app\modules\manage\widgets\DropZoneWidget;
use app\models\LocCountries;
use app\models\LocRegions;
use app\models\LocCities;
use app\models\Orders;
use app\widgets\FroalaSimpleEditorWidget;

/**
 * @var $this \yii\web\View
 * @var $page \app\models\Pages
 *
 * @var $model \app\models\Camps
 *
 * @var $templates \app\models\Camps[]
 *
 * @var $base_item \app\models\BaseItems
 * @var $base_items \app\models\BaseItems[]
 *
 * @var $base_period \app\models\BasePeriods
 * @var $base_periods \app\models\BasePeriods[]
 *
 * @var $base_placement \app\models\BasePlacements
 * @var $base_placements \app\models\BasePlacements[]
 *
 * @var $camp_placement \app\models\CampsPlacement
 * @var $camp_about \app\models\CampsAbout
 * @var $camp_media \app\models\CampsMedia
 * @var $camp_client \app\models\CampsClient
 * @var $camp_contract \app\models\CampsContract
 * @var $camp_contacts \app\models\CampsContacts
 */

$this->title = $page->title;
MetaHelper::setMeta($page, $this);

$opts = ['inputOptions' => ['class' => 'form-control'],
         'template' => '<div class="col-xs-12 col-md-3">{input}</div>{label}<div class="col-xs-12 col-md-offset-3 col-md-9">{error}</div>',
         'labelOptions' => ['class' => 'col-xs-12 col-md-9 control-label text-left']];

$chbx = ['template' => '<div class="col-xs-12">{input}{error}</div>'];
$txta = ['template' => '{label}<div class="col-xs-12">{input}{error}</div>', 'labelOptions' => ['class' => 'col-xs-12 control-label text-left p-b-5']];

$w100 = ['inputOptions' => ['class' => 'form-control w-100']];
$w150 = ['inputOptions' => ['class' => 'form-control w-150']];
$dtpk = ['inputOptions' => ['class' => 'form-control w-150 datepickers']];
$w200 = ['inputOptions' => ['class' => 'form-control w-200']];
$w250 = ['inputOptions' => ['class' => 'form-control w-250']];

$items_class = 'camp-register-items';
$periods_class = 'camp-register-periods';
$placement_class = 'camp-register-placements';

$form_config = [
    'fieldConfig' => [
        'template' => '<div class="col-xs-12 col-md-4 text-right">{label}</div><div class="col-xs-12 col-md-8">{input}{error}</div>',
        'labelOptions' => ['class' => 'control-label']
    ]
];
?>
<div class="layout-container">
    <h1 class="index-title"><?= $page->title ?></h1>
    
    <div class="camp-register-steps-container">
        <div class="steps-corner"></div>
    
        <a href="#step-1" class="camp-register-steps camp-register-step-1 active">О лагере</a>
        <a href="#step-2" class="camp-register-steps camp-register-step-2">Размещение</a>
        <a href="#step-3" class="camp-register-steps camp-register-step-3">Фото и видео</a>
        <a href="#step-4" class="camp-register-steps camp-register-step-4">Дополнительно</a>
        <a href="#step-5" class="camp-register-steps camp-register-step-5">Контакты</a>
        <a href="#step-6" class="camp-register-steps camp-register-step-6">Договор</a>
        <a href="#step-7" class="camp-register-steps camp-register-step-7">Смены и цены</a>
    </div>
    
    <div class="m-b-75">
        
        <? if ($model->isNewRecord && $templates): ?>
            <div class="required pull-right">
                <form action="" method="get">
                    <?= Html::dropDownList('template', null, $templates, [
                        'prompt' => '- Скопировать данные из лагеря -',
                        'class' => 'form-control custom-select min-width-300',
                        'onchange' => "change_template(this)"
                    ]); ?>
                </form>
                <script>
                    function change_template(obj) {
                        var $obj = $(obj);
                        
                        if (confirm("При использовании шаблона будут стерты\nвсе данные, которые вы внесли до этого.\nПодтверждаете выбор лагеря-шаблона?")) {
                            $obj.closest('form').submit();
                        } else {
                            $obj.find('option:first').prop('selected', true);
                            $obj.val($obj.find('option:first').val());
                        }
                    }
                </script>
            </div>
        <? else: ?>
            <div class="required pull-right p-t-4"><label for=""></label> - поля, обязательные для заполнения</div>
        <?endif; ?>
        
        <div id="form-step-1" class="camp-register-forms">
            <h3>Шаг 1. Данные о лагере и его местоположении</h3>
    
            <? $form = ActiveForm::begin($form_config); ?>
            <?= Html::hiddenInput('step', 1); ?>
            
            <div class="well well-sm m-t-20 p-25">
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <?= $form->field($camp_about, 'name_short')->textInput(['placeholder' => 'Например: Детский лагерь "Ромашка"']) ?>
                        <?= $form->field($camp_about, 'name_full')->textInput(['placeholder' => 'Например: ООО "Ромашка"']) ?>
                        <?= $form->field($camp_about, 'name_org')->textInput(['placeholder' => 'Например: ООО "Детство"']) ?>
                        
                        <?= $form->field($camp_about, 'name_variants')->textInput(['placeholder' => 'Через запятую, в т.ч. названия по-английски']) ?>
                        <?= FroalaSimpleEditorWidget::widget([
                            'model' => $camp_about,
                            'field' => 'name_details',
                            'type' => FroalaSimpleEditorWidget::TYPE_ROW,
                            'params' => ['placeholderText' => 'Кратко о лагере: аудитория, расположение, программа, развлечения, режим и т.п.']
                        ]); ?>
                    </div>
                    <div class="col-xs-12 col-md-6">
        
                        <div class="row <?= $camp_about->isAttributeRequired('age_from') ? 'required' : '' ?>">
                            <label for="" class="control-label col-xs-12 col-md-4">Возраст детей</label>
                            <div class="col-xs-12 col-md-8">
                                <table>
                                    <tr>
                                        <td valign="top" width="150">
                                            <?= $form->field($camp_about, 'age_from', [
                                                'template' => str_replace(['{addon}','{class}'], ['лет','w-150'], Yii::$app->params['group_template_simple']),
                                            ])->input('number', [
                                                'min' => 1, 'max' => 30, 'step' => 1,
                                                'placeholder' => 'От'
                                            ]) ?>
                                        </td>
                                        <td valign="top">
                                            <p class="form-control-static">&nbsp;&nbsp;&ndash;&nbsp;&nbsp;</p>
                                        </td>
                                        <td valign="top" width="150">
                                            <?= $form->field($camp_about, 'age_to', [
                                                'template' => str_replace(['{addon}','{class}'], ['лет','w-150'], Yii::$app->params['group_template_simple']),
                                            ])->input('number', [
                                                'min' => 1, 'max' => 30, 'step' => 1,
                                                'placeholder' => 'До'
                                            ]) ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
    
                        <?= $form->field($camp_about, 'made_year', [
                            'template' => str_replace(['{addon}','{class}'], ['году','w-150'], Yii::$app->params['group_template'])
                        ])->input('number', ['min' => 1900, 'max' => date('Y'), 'step' => 1]) ?>
    
                        <?= $form->field($camp_about, 'count_builds',[
                            'template' => str_replace(['{addon}','{class}'], ['шт','w-150'], Yii::$app->params['group_template'])
                        ])->input('number', ['min' => 1, 'max' => 100, 'step' => 1]) ?>
        
                        <?= $form->field($camp_about, 'area', [
                            'template' => str_replace(['{addon}','{class}'], ['га','w-150'], Yii::$app->params['group_template'])
                        ]) ?>
        
                        <?= $form->field($camp_about, 'count_places', [
                            'template' => str_replace(['{addon}','{class}'], ['мест','w-150'], Yii::$app->params['group_template'])
                        ])->input('number', ['min' => 10, 'step' => 1]) ?>
        
                        <?= $form->field($camp_about, 'count_per_year', [
                            'template' => str_replace(['{addon}','{class}'], ['детей','w-150'], Yii::$app->params['group_template'])
                        ])->input('number', ['min' => 1, 'step' => 1]) ?>
                    </div>
                </div>
    
                <div class="line-separator m-t-5 m-b-25"></div>
                
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <?= $form->field($camp_about, 'loc_country')->dropDownList(LocCountries::getFilterList(), [
                            'class' => 'form-control custom-select',
                            'prompt' => '- Выбор страны -'
                        ]) ?>
                        <?= $form->field($camp_about, 'loc_region')->dropDownList(LocRegions::getFilterList($camp_about->loc_country), [
                            'class' => 'form-control custom-select',
                        ]) ?>
                        <?= $form->field($camp_about, 'loc_city')->dropDownList(LocCities::getFilterList($camp_about->loc_region), [
                            'class' => 'form-control custom-select',
                        ]) ?>
                        
                        <?= $form->field($camp_about, 'loc_distance_to_city', [
                            'template' => str_replace(['{addon}','{class}'], ['км','w-auto'], Yii::$app->params['group_template'])
                        ])->input('number', [
                            'min' => 0, 'step' => 1,
                            'placeholder' => 'или до другого населенного пункта'
                        ]) ?>

                        <?= $form->field($camp_about, 'trans_escort_cities_f[]')->dropDownList($camp_about->trans_escort_cities_f, [
                            'class' => 'form-control select2',
                            'multiple' => true,
                            'data-data-type' => 'json',
                            'data-minimum-input-length' => 2,
                            'data-ajax--url' => Url::to(['/ajax/escort']),
                            'data-ajax--delay' => 750,
                            'data-ajax--cache' => 'true',
                            'data-type' => 'escort',
                            'data-placeholder' => '- Начните вводить название города -'
                        ])->label('Сопровождение из') ?>

                        <?php
                        $this->registerJs("
                            $('#" . Html::getInputId($camp_about, 'trans_escort_cities_f') . "').val([" . implode(',', array_keys($camp_about->trans_escort_cities_f)) . "]).trigger('change');
                        ");
                        ?>
                        
                        <?= $form->field($camp_about, 'loc_address')->textInput([
                            'placeholder' => 'Без указания города, только адрес'
                        ]) ?>
                        <div class="form-group">
                            <div class="col-xs-12 col-md-offset-4 col-md-7">
                                <button type="button" class="btn btn-link" onclick="toggle_map('#loc_address')"><?= $camp_about->getAttributeLabel('loc_coords') ?></button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xs-12 col-md-6">
                        <?= FroalaSimpleEditorWidget::widget([
                            'model' => $camp_about,
                            'field' => 'loc_routing',
                            'type' => FroalaSimpleEditorWidget::TYPE_ROW,
                            'params' => ['placeholderText' => 'От ближайшего крупного населенного пункта, указанного в поле ' . $camp_about->getAttributeLabel('loc_city')]
                        ]); ?>
                        <div class="m-t-40">
                            <?= $form->field($camp_about, 'trans_in_price')->checkbox(['class' => 'ichecks']) ?>
                            <?= $form->field($camp_about, 'trans_with_escort')->checkbox(['class' => 'ichecks']) ?>
                        </div>
                    </div>
                </div>
    
                <div id="loc_address" class="form-group hidden">
                    <div class="col-xs-12">
                        <?= \app\widgets\YandexMap::widget([
                            'model' => $camp_about,
                
                            'width' => '100%',
                            'height' => '400px',

                            'field_lat' => 'loc_coords_f[lat]',
                            'field_lng' => 'loc_coords_f[lng]',
                            'field_zoom' => 'loc_coords_f[zoom]',

                            'field_country' => 'loc_country',
                            'field_region' => 'loc_region',
                            'field_city' => 'loc_city',
                            
                            'field_watcher' => 'loc_address',
                
                            'hintContent' => '',
                            'balloonContent' => Html::encode($camp_about->loc_address)
                        ]) ?>
                    </div>
                </div>
    
                <div class="line-separator m-t-5 m-b-25"></div>
                
                <div class="form-group">
                    <div class="col-xs-12 col-md-2 text-right">
                        <label>
                            <?= $camp_about->getAttributeLabel('tags_types') ?>
                            <?= $camp_about->isAttributeRequired('tags_types') ? '<span class="required">*</span>' : '' ?>
                        </label>
                        <br><small class="text-muted">От 1 до 5 опций</small>
                        <?= Html::error($camp_about, 'tags_types_f', ['class' => 'help-block']); ?>
                    </div>
                    <div class="col-xs-12 col-md-10">
                        <?= Html::activeCheckboxList($camp_about, "tags_types_f", TagsTypes::getFilterList(), [
                            'class' => 'ichecks',
                            'unselect' => null,
                            'item' => function($index, $label, $name, $checked, $value) use ($camp_about) {
                                $name = str_replace('[]', "[{$index}]", $name);
                                return Html::tag('div', Html::checkbox($name, $checked, [
                                    'label' => $label,
                                    'value' => $value,
                                    'class' => 'ichecks'
                                ]), ['class' => 'checkbox-list']);
                            }
                        ]); ?>
                    </div>
                </div>
    
                <div class="line-separator m-t-5 m-b-25"></div>
    
                <div class="form-group">
                    <div class="col-xs-12 col-md-2 text-right">
                        <label>
                            <?= $camp_about->getAttributeLabel('tags_sport') ?>
                            <?= $camp_about->isAttributeRequired('tags_sport') ? '<span class="required">*</span>' : '' ?>
                        </label>
                        <br><small class="text-muted">Не менее 1 опции</small>
                        <?= Html::error($camp_about, 'tags_sport_f', ['class' => 'help-block']); ?>
                    </div>
                    <div class="col-xs-12 col-md-10">
                        <?= Html::activeCheckboxList($camp_about, "tags_sport_f", TagsSport::getFilterList(), [
                            'class' => 'ichecks',
                            'unselect' => null,
                            'item' => function($index, $label, $name, $checked, $value) use ($camp_about) {
                                $name = str_replace('[]', "[{$index}]", $name);
                                return Html::tag('div', Html::checkbox($name, $checked, [
                                    'label' => $label,
                                    'value' => $value,
                                    'class' => 'ichecks'
                                ]), ['class' => 'checkbox-list']);
                            }
                        ]); ?>
                    </div>
                </div>
    
                <div class="line-separator m-t-5 m-b-25"></div>
    
                <div class="form-group">
                    <div class="col-xs-12 col-md-2 text-right">
                        <label>
                            <?= $camp_about->getAttributeLabel('tags_places') ?>
                            <?= $camp_about->isAttributeRequired('tags_places') ? '<span class="required">*</span>' : '' ?>
                        </label>
                        <br><small class="text-muted">Не менее 1 опции</small>
                        <?= Html::error($camp_about, 'tags_places_f', ['class' => 'help-block']); ?>
                    </div>
                    <div class="col-xs-12 col-md-10">
                        <?= Html::activeCheckboxList($camp_about, "tags_places_f", TagsPlaces::getFilterList(), [
                            'class' => 'ichecks',
                            'unselect' => null,
                            'item' => function($index, $label, $name, $checked, $value) use ($camp_about) {
                                $name = str_replace('[]', "[{$index}]", $name);
                                return Html::tag('div', Html::checkbox($name, $checked, [
                                    'label' => $label,
                                    'value' => $value,
                                    'class' => 'ichecks'
                                ]), ['class' => 'checkbox-list']);
                            }
                        ]); ?>
                    </div>
                </div>
    
                <div class="line-separator m-t-5 m-b-25"></div>
    
                <div class="form-group">
                    <div class="col-xs-12 col-md-2 text-right">
                        <label>
                            <?= $camp_about->getAttributeLabel('tags_services') ?>
                            <?= $camp_about->isAttributeRequired('tags_services') ? '<span class="required">*</span>' : '' ?>
                        </label>
                        <br><small class="text-muted">Не менее 1 опции</small>
                        <?= Html::error($camp_about, 'tags_services_f', ['class' => 'help-block']); ?>
                    </div>
                    <div class="col-xs-12 col-md-10">
                        <?= Html::activeCheckboxList($camp_about, "tags_services_f", \app\models\ComfortTypes::getFilterList(), [
                            'class' => 'ichecks',
                            'unselect' => null,
                            'item' => function($index, $label, $name, $checked, $value) use ($camp_about) {
                                $name = str_replace('[]', "[{$index}]", $name);
                                return Html::tag('div', Html::checkbox($name, $checked, [
                                    'label' => $label,
                                    'value' => $value,
                                    'class' => 'ichecks'
                                ]), ['class' => 'checkbox-list']);
                            }
                        ]); ?>
                    </div>
                </div>
            </div>
    
            <div class="row">
                <div class="col-xs-offset-6 col-xs-6">
                    <button class="btn btn-lg btn-block btn-primary btn-steps btn-step-next">Далее&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
                </div>
            </div>
    
            <? ActiveForm::end(); ?>
        </div>
    
        <div id="form-step-2" class="camp-register-forms hidden">
            <h3>Шаг 2. Условия размещения и проживания детей</h3>
    
            <? $form = ActiveForm::begin($form_config); ?>
            <?= Html::hiddenInput('step', 2); ?>
            
            <div class="well well-sm m-t-20 p-25">
    
                <?= $form->field($camp_placement, 'is_without_places')->checkbox(['class' => 'ichecks']) ?>
                <?php
                    $this->registerJs("
                    $('#" . Html::getInputId($camp_placement, 'is_without_places') . "').on('ifToggled', function(){
                        if (this.checked) {
                            $('#places_row').addClass('hidden');
                        } else {
                            $('#places_row').removeClass('hidden');
                        }
                    });
                ");
    
                if ($camp_placement->is_without_places) $this->registerJs("$('#places_row').addClass('hidden');");
                ?>
    
                <div class="form-group m-b-20" id="places_row">
                    <div class="col-xs-12 col-md-4 text-right">
                        <label for="" class="control-label">Варианты размещения <span class="required">*</span></label>
                    </div>
                    <div class="col-xs-12 col-md-8">
                        <div class="row">
                            <div class="col-xs-5">
                                <p class="form-control-static">Тип удобств</p>
                            </div>
                            <div class="col-xs-6">
                                <p class="form-control-static">Скольки местное размещение</p>
                            </div>
                            <div class="col-xs-1 text-left"></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12" id="placement_items">
                                <? if ($model->isNewRecord || empty($base_placements)): ?>
                                    <?= str_replace('{index}', 0, $this->render('camp-register-add-placement', ['model_placement' => $base_placement, 'class' => $placement_class])) ?>
                                <? else: ?>
                                    <? foreach ($base_placements AS $mk => $mi): ?>
                                        <?= str_replace('{index}', $mk, $this->render('camp-register-add-placement', ['model_placement' => $mi, 'class' => $placement_class])) ?>
                                    <? endforeach; ?>
                                <? endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?= $form->field($camp_placement, 'placement_count_eat', [
                    'template' => str_replace(['{addon}','{class}'], ['- разовое','w-200'], Yii::$app->params['group_template'])
                ])->input('number', ['min' => 1, 'max' => 30, 'step' => 1]) ?>
                
                <div class="m-b-20">
                    <?= FroalaSimpleEditorWidget::widget([
                        'model' => $camp_placement,
                        'field' => 'placement_details',
                        'params' => ['placeholderText' => 'Подробное описание лагеря и всех его преимуществ']
                    ]); ?>
                </div>
    
                <div class="m-b-20">
                    <?= FroalaSimpleEditorWidget::widget([
                        'model' => $camp_placement,
                        'field' => 'placement_groups',
                        'params' => ['placeholderText' => 'Для групповых заездов']
                    ]); ?>
                </div>
    
                <div class="m-b-20">
                    <?= FroalaSimpleEditorWidget::widget([
                        'model' => $camp_placement,
                        'field' => 'placement_med',
                        'params' => ['placeholderText' => 'Например: медпункт и сопровождающая медсестра']
                    ]); ?>
                </div>
    
                <div class="m-b-20">
                    <?= FroalaSimpleEditorWidget::widget([
                        'model' => $camp_placement,
                        'field' => 'placement_security',
                        'params' => ['placeholderText' => 'Например: собственная служба охраны']
                    ]); ?>
                </div>
    
                <div class="m-b-20">
                    <?= FroalaSimpleEditorWidget::widget([
                        'model' => $camp_placement,
                        'field' => 'placement_program',
                        'params' => ['placeholderText' => 'Например: походы, плавание, художественная самодеятельность и т.п.']
                    ]); ?>
                </div>
    
                <div class="m-b-20">
                    <?= FroalaSimpleEditorWidget::widget([
                        'model' => $camp_placement,
                        'field' => 'placement_regime_day',
                        'params' => ['placeholderText' => 'Например: 8:00 подъем, 8:10 зарядка и т.д.']
                    ]); ?>
                </div>
    
                <div class="m-b-20">
                    <?= FroalaSimpleEditorWidget::widget([
                        'model' => $camp_placement,
                        'field' => 'placement_regime_tour',
                        'params' => ['placeholderText' => 'Например: 1-й день - знакомство и размещение, 2-й день - подъем на гору и т.д.']
                    ]); ?>
                </div>
            </div>
    
            <div class="row">
                <div class="col-xs-6">
                    <button class="btn btn-lg btn-block btn-default btn-steps btn-step-prev" type="button"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Назад</button>
                </div>
                <div class="col-xs-6">
                    <button class="btn btn-lg btn-block btn-primary btn-steps btn-step-next">Далее&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
                </div>
            </div>
    
            <? ActiveForm::end(); ?>
        </div>
    
        <div id="form-step-3" class="camp-register-forms hidden">
            <h3>Шаг 3. Покажите ваш лагерь на фото и видео</h3>
    
            <? $form = ActiveForm::begin($form_config); ?>
            <?= Html::hiddenInput('step', 3); ?>
            
            <div class="well well-sm m-t-20 p-25">
    
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group <?= $camp_media->isAttributeRequired('photo_main') ? 'required' : '' ?>">
                            <label class="control-label text-left col-xs-12 p-b-5" for="<?= Html::getInputId($model, 'photo_main') ?>">
                                <?= $camp_media->getAttributeLabel('photo_main') ?> (аватар)
                            </label>
                            <div class="col-xs-12">
                                <?= DropZoneWidget::widget([
                                    'zone_id' => Html::getInputId($camp_media, 'photo_main'),
                                    'model' => $camp_media,
                                    'field' => 'photo_main',
                                    'url' => Url::to(['/ajax/upload']),
                                ]) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group <?= $camp_media->isAttributeRequired('photo_partner') ? 'required' : '' ?>">
                            <label class="control-label text-left col-xs-12 p-b-5" for="<?= Html::getInputId($model, 'photo_partner') ?>">
                                <?= $camp_media->getAttributeLabel('photo_partner') ?> (логотип)
                            </label>
                            <div class="col-xs-12">
                                <?= DropZoneWidget::widget([
                                    'zone_id' => Html::getInputId($camp_media, 'photo_partner'),
                                    'model' => $camp_media,
                                    'field' => 'photo_partner',
                                    'url' => Url::to(['/ajax/upload']),
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
        
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group <?= $camp_media->isAttributeRequired('photos_room_f') ? 'required' : '' ?>">
                            <label class="control-label text-left col-xs-12 p-b-5" for="<?= Html::getInputId($model, 'photos_room_f') ?>">
                                <?= $camp_media->getAttributeLabel('photos_room_f') ?> (от 1 до 3-х фотографий)
                            </label>
                            <div class="col-xs-12">
                                <?= DropZoneWidget::widget([
                                    'zone_id' => Html::getInputId($camp_media, 'photos_room_f'),
                                    'model' => $camp_media,
                                    'field' => 'photos_room_f[]',
                                    'url' => Url::to(['/ajax/upload']),
                                    'max_files' => 3
                                ]) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group <?= $camp_media->isAttributeRequired('photos_sport_f') ? 'required' : '' ?>">
                            <label class="control-label text-left col-xs-12 p-b-5" for="<?= Html::getInputId($model, 'photos_sport_f') ?>">
                                <?= $camp_media->getAttributeLabel('photos_sport_f') ?> (от 1 до 3-х фотографий)
                            </label>
                            <div class="col-xs-12">
                                <?= DropZoneWidget::widget([
                                    'zone_id' => Html::getInputId($camp_media, 'photos_sport_f'),
                                    'model' => $camp_media,
                                    'field' => 'photos_sport_f[]',
                                    'url' => Url::to(['/ajax/upload']),
                                    'max_files' => 3
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
        
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group <?= $camp_media->isAttributeRequired('photos_area_f') ? 'required' : '' ?>">
                            <label class="control-label text-left col-xs-12 p-b-5" for="<?= Html::getInputId($model, 'photos_area_f') ?>">
                                <?= $camp_media->getAttributeLabel('photos_area_f') ?> (от 1 до 3-х фотографий)
                            </label>
                            <div class="col-xs-12">
                                <?= DropZoneWidget::widget([
                                    'zone_id' => Html::getInputId($camp_media, 'photos_area_f'),
                                    'model' => $camp_media,
                                    'field' => 'photos_area_f[]',
                                    'url' => Url::to(['/ajax/upload']),
                                    'max_files' => 3
                                ]) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group <?= $camp_media->isAttributeRequired('photos_eating_f') ? 'required' : '' ?>">
                            <label class="control-label text-left col-xs-12 p-b-5" for="<?= Html::getInputId($model, 'photos_eating_f') ?>">
                                <?= $camp_media->getAttributeLabel('photos_eating_f') ?> (от 1 до 3-х фотографий)
                            </label>
                            <div class="col-xs-12">
                                <?= DropZoneWidget::widget([
                                    'zone_id' => Html::getInputId($camp_media, 'photos_eating_f'),
                                    'model' => $camp_media,
                                    'field' => 'photos_eating_f[]',
                                    'url' => Url::to(['/ajax/upload']),
                                    'max_files' => 3
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
        
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group <?= $camp_media->isAttributeRequired('photos_comfort_f') ? 'required' : '' ?>">
                            <label class="control-label text-left col-xs-12 p-b-5" for="<?= Html::getInputId($model, 'photos_comfort_f') ?>">
                                <?= $camp_media->getAttributeLabel('photos_comfort_f') ?> (от 1 до 3-х фотографий)
                            </label>
                            <div class="col-xs-12">
                                <?= DropZoneWidget::widget([
                                    'zone_id' => Html::getInputId($camp_media, 'photos_comfort_f'),
                                    'model' => $camp_media,
                                    'field' => 'photos_comfort_f[]',
                                    'url' => Url::to(['/ajax/upload']),
                                    'max_files' => 3
                                ]) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group <?= $camp_media->isAttributeRequired('photos_med_f') ? 'required' : '' ?>">
                            <label class="control-label text-left col-xs-12 p-b-5" for="<?= Html::getInputId($model, 'photos_med_f') ?>">
                                <?= $camp_media->getAttributeLabel('photos_med_f') ?> (до 3-х фотографий)
                            </label>
                            <div class="col-xs-12">
                                <?= DropZoneWidget::widget([
                                    'zone_id' => Html::getInputId($camp_media, 'photos_med_f'),
                                    'model' => $camp_media,
                                    'field' => 'photos_med_f[]',
                                    'url' => Url::to(['/ajax/upload']),
                                    'max_files' => 3
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
        
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group <?= $camp_media->isAttributeRequired('photos_security_f') ? 'required' : '' ?>">
                            <label class="control-label text-left col-xs-12 p-b-5" for="<?= Html::getInputId($model, 'photos_security_f') ?>">
                                <?= $camp_media->getAttributeLabel('photos_security_f') ?> (до 3-х фотографий)
                            </label>
                            <div class="col-xs-12">
                                <?= DropZoneWidget::widget([
                                    'zone_id' => Html::getInputId($camp_media, 'photos_security_f'),
                                    'model' => $camp_media,
                                    'field' => 'photos_security_f[]',
                                    'url' => Url::to(['/ajax/upload']),
                                    'max_files' => 3
                                ]) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group <?= $camp_media->isAttributeRequired('photos_concert_hall_f') ? 'required' : '' ?>">
                            <label class="control-label text-left col-xs-12 p-b-5" for="<?= Html::getInputId($model, 'photos_concert_hall_f') ?>">
                                <?= $camp_media->getAttributeLabel('photos_concert_hall_f') ?> (до 3-х фотографий)
                            </label>
                            <div class="col-xs-12">
                                <?= DropZoneWidget::widget([
                                    'zone_id' => Html::getInputId($camp_media, 'photos_concert_hall_f'),
                                    'model' => $camp_media,
                                    'field' => 'photos_concert_hall_f[]',
                                    'url' => Url::to(['/ajax/upload']),
                                    'max_files' => 3
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
    
    
                <div class="form-group <?= $camp_media->isAttributeRequired('photos_others_f') ? 'required' : '' ?>">
                    <label class="control-label text-left col-xs-12 p-b-5" for="<?= Html::getInputId($model, 'photos_others_f') ?>">
                        <?= $camp_media->getAttributeLabel('photos_others_f') ?> (до 10-и фотографий)
                    </label>
                    <div class="col-xs-12">
                        <?= DropZoneWidget::widget([
                            'zone_id' => Html::getInputId($camp_media, 'photos_others_f'),
                            'model' => $camp_media,
                            'field' => 'photos_others_f[]',
                            'url' => Url::to(['/ajax/upload']),
                            'max_files' => 10
                        ]) ?>
                    </div>
                </div>
    
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <?= $form->field($camp_media, 'videos_f[0]', $txta)->textarea([
                            'class' => 'form-control h-142',
                            'placeholder' => 'Вставьте код видеозаписи из YouTube, например: <iframe width="560" height="315" src="https://www.youtube.com/embed/43kcu11H_qY" frameborder="0" allowfullscreen></iframe>'
                        ])->label('Видеозапись 1') ?>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <?= $form->field($camp_media, 'videos_f[1]', $txta)->textarea([
                            'class' => 'form-control h-142',
                            'placeholder' => 'Вставьте код видеозаписи из YouTube, например: <iframe width="560" height="315" src="https://www.youtube.com/embed/43kcu11H_qY" frameborder="0" allowfullscreen></iframe>'
                        ])->label('Видеозапись 2') ?>
                    </div>
                </div>
            </div>
    
            <div class="row">
                <div class="col-xs-6">
                    <button class="btn btn-lg btn-block btn-default btn-steps btn-step-prev" type="button"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Назад</button>
                </div>
                <div class="col-xs-6">
                    <button class="btn btn-lg btn-block btn-primary btn-steps btn-step-next">Далее&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
                </div>
            </div>
    
            <? ActiveForm::end(); ?>
        </div>
    
        <div id="form-step-4" class="camp-register-forms hidden">
            <h3>Шаг 4. Дополнительные сведения для родителей и их детей</h3>
    
            <? $form = ActiveForm::begin($form_config); ?>
            <?= Html::hiddenInput('step', 4); ?>
            
            <div class="well well-sm m-t-20 p-25">
                <?= $form->field($camp_client, 'info_visa')->dropDownList(\app\models\CampsClient::getVisaTypes(), [
                    'class' => 'form-control custom-select w-250'
                ]) ?>
                
                <? foreach (['info_common', 'info_docs', 'info_payment', 'info_dops', 'info_bags'] AS $field_floala): ?>
                    <div class="m-b-20">
                        <?= FroalaSimpleEditorWidget::widget([
                            'model' => $camp_client,
                            'field' => $field_floala,
                        ]); ?>
                    </div>
                <? endforeach; ?>
            </div>
    
            <div class="row">
                <div class="col-xs-6">
                    <button class="btn btn-lg btn-block btn-default btn-steps btn-step-prev" type="button"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Назад</button>
                </div>
                <div class="col-xs-6">
                    <button class="btn btn-lg btn-block btn-primary btn-steps btn-step-next">Далее&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
                </div>
            </div>
    
            <? ActiveForm::end(); ?>
        </div>
    
        <div id="form-step-5" class="camp-register-forms hidden">
            <h3>Шаг 5. Контакты для связи с вами и вашим персоналом</h3>
    
            <? $form = ActiveForm::begin($form_config); ?>
            <?= Html::hiddenInput('step', 5); ?>
            
            <div class="well well-sm m-t-20 p-25">
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <?= $form->field($camp_contacts, 'boss_fio') ?>
                        <?= $form->field($camp_contacts, 'boss_phone') ?>
                        <?= $form->field($camp_contacts, 'boss_email') ?>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <?= $form->field($camp_contacts, 'worker_fio')->textInput(['placeholder' => 'Ответственный за сотрудничество с нами']) ?>
                        <?= $form->field($camp_contacts, 'worker_phone')->textInput(['placeholder' => 'Ответственный за сотрудничество с нами']) ?>
                        <?= $form->field($camp_contacts, 'worker_email')->textInput(['placeholder' => 'Ответственный за сотрудничество с нами']) ?>
                    </div>
                </div>
    
                <div class="line-separator m-t-5 m-b-25"></div>
    
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <?= $form->field($camp_contacts, 'office_address')->textInput(['placeholder' => 'Например: Москва, ул.Набережная, д.5, офис 310']) ?>
                        <?= $form->field($camp_contacts, 'office_phone')->textInput(['placeholder' => 'Основной телефон']) ?>
                        <?= $form->field($camp_contacts, 'office_mobile')->textInput(['placeholder' => 'Дополнительный телефон']) ?>

                        <div class="form-group">
                            <div class="col-xs-12 col-md-offset-4 col-md-7">
                                <button type="button" class="btn btn-link" onclick="$('#cont_address').toggleClass('hidden')">
                                    <?= $camp_contacts->getAttributeLabel('office_coords') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <?= $form->field($camp_contacts, 'office_route')->textarea([
                            'placeholder' => 'Как проехать к офису и график работы',
                            'class' => 'form-control h-142'
                        ]) ?>
                    </div>
                </div>

                <div id="cont_address" class="form-group hidden">
                    <div class="col-xs-12">
                        <?= \app\widgets\YandexMap::widget([
                            'model' => $camp_contacts,

                            'width' => '100%',
                            'height' => '400px',

                            'field_lat' => 'office_coords_f[lat]',
                            'field_lng' => 'office_coords_f[lng]',
                            'field_zoom' => 'office_coords_f[zoom]',

                            'field_watcher' => 'office_address',

                            'hintContent' => '',
                            'balloonContent' => Html::encode($camp_contacts->office_address)
                        ]) ?>
                    </div>
                </div>
    
                <div class="line-separator m-t-5 m-b-25"></div>
    
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <div class="col-xs-12 col-md-offset-4 col-md-8">
                                <p class="form-control-static strong text-success">Контактные данные для уведомлений</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <?= $form->field($camp_contacts, 'notif_order_phone')->textInput([
                            'placeholder' => $camp_contacts->getAttributeLabel('notif_order_phone')
                        ])->label('Номер телефона') ?>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <?= $form->field($camp_contacts, 'notif_order_emails')->textInput([
                            'placeholder' => 'Email-ы для уведомлений о брони, через запятую'
                        ])->label('Email уведомления') ?>
                    </div>
                </div>
    
                <div class="line-separator m-t-5 m-b-25"></div>
    
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <div class="col-xs-12 col-md-offset-4 col-md-8">
                                <p class="form-control-static strong text-success">Социальные сети</p>
                            </div>
                        </div>
                        <?= $form->field($camp_contacts, 'social_vk')->textInput(['placeholder' => 'Ссылка на группу ВКонтакте']) ?>
                        <?= $form->field($camp_contacts, 'social_ok')->textInput(['placeholder' => 'Ссылка на группу в Одноклассниках']) ?>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <?= $form->field($camp_contacts, 'social_fb')->textInput(['placeholder' => 'Ссылка на группу Facebook']) ?>
                        <?= $form->field($camp_contacts, 'social_ig')->textInput(['placeholder' => 'Ссылка на страницу Instagram']) ?>
                        <?= $form->field($camp_contacts, 'site_url') ?>
                    </div>
                </div>
            </div>
    
            <div class="row">
                <div class="col-xs-6">
                    <button class="btn btn-lg btn-block btn-default btn-steps btn-step-prev" type="button"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Назад</button>
                </div>
                <div class="col-xs-6">
                    <button class="btn btn-lg btn-block btn-primary btn-steps btn-step-next">Далее&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
                </div>
            </div>
    
            <? ActiveForm::end(); ?>
        </div>
    
        <div id="form-step-6" class="camp-register-forms hidden">
            <h3>Шаг 6. Данные договора, опции, юридическое лицо</h3>
    
            <? $form = ActiveForm::begin($form_config); ?>
            <?= Html::hiddenInput('step', 6); ?>
            
            <div class="well well-sm m-t-20 p-t-25">
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <?= $form->field($camp_contract, 'contract_inn')->textInput(['maxlength' => 10]) ?>
                        <?= $form->field($camp_contract, 'contract_ogrn_serial') ?>
                        <?= $form->field($camp_contract, 'contract_ogrn_number')->textInput(['maxlength' => 15]) ?>
                        <?= $form->field($camp_contract, 'contract_ogrn_date_f', $dtpk)->textInput(['maxlength' => 10]) ?>
    
                        <div class="separator"></div>
    
                        <?= $form->field($camp_contract, 'contract_period_type', $w200)->dropDownList(CampsContract::getPeriodTypes()) ?>
    
                        <div class="form-group periods-block hidden">
                            <div class="col-xs-12 col-md-4 text-right">
                                <label for="" class="control-label">Периоды</label>
                            </div>
                            <div class="col-xs-12 col-md-8">
                                <table id="base_periods" class="table">
                                    <tbody>
                                    <? if ($model->isNewRecord || empty($base_periods)): ?>
                                        <?= str_replace('{index}', 0, $this->render('camp-register-add-period', ['model_period' => $base_period, 'class' => $periods_class])) ?>
                                    <? else: ?>
                                        <? foreach ($base_periods AS $mk => $mp): ?>
                                            <?= str_replace('{index}', $mk, $this->render('camp-register-add-period', ['model_period' => $mp, 'class' => $periods_class])) ?>
                                        <? endforeach; ?>
                                    <? endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="p-t-7">
                            <?= $form->field($camp_contract, 'opt_gos_compensation', $chbx)->checkbox(['class' => 'ichecks', 'uncheck' => null]) ?>
                        </div>
                        <div class="p-t-7">
                            <?= $form->field($camp_contract, 'opt_group_use', $chbx)->checkbox(['class' => 'ichecks', 'uncheck' => null]) ?>
                        </div>
                        <?= $form->field($camp_contract, 'opt_group_discount', $opts)->input('number', ['min' => 1, 'max' => 100, 'disabled' => !$camp_contract->opt_group_use]) ?>
                        <?= $form->field($camp_contract, 'opt_group_count', $opts)->input('number', ['min' => 2, 'max' => 100, 'disabled' => !$camp_contract->opt_group_use]) ?>
                        <?= $form->field($camp_contract, 'opt_group_guides', $opts)->input('number', ['min' => 1, 'disabled' => !$camp_contract->opt_group_use]) ?>
                    </div>
                </div>
            </div>
    
            <div class="row">
                <div class="col-xs-6">
                    <button class="btn btn-lg btn-block btn-default btn-steps btn-step-prev" type="button"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Назад</button>
                </div>
                <div class="col-xs-6">
                    <button class="btn btn-lg btn-block btn-primary btn-steps btn-step-next">Далее&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
                </div>
            </div>
    
            <? ActiveForm::end(); ?>
        </div>
        
        <div id="form-step-7" class="camp-register-forms hidden">
            <h3>Шаг 7. Расписание смен и цены</h3>
    
            <? $form = ActiveForm::begin($form_config); ?>
            <?= Html::hiddenInput('step', 7); ?>
    
            <div class="well well-sm m-t-20 p-t-25">
                <table id="base_items" class="table table-condensed">
                    <thead>
                        <tr>
                            <th class="text-left">Короткое и полное<br>названия смены</th>
                            <th class="text-center" width="150">Диапазон дат<br>для смены</th>
                            <th class="text-center" width="150">Кол-во и цена<br>путевок</th>
                            <th width="50">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="<?= $items_class ?>">
                            <td></td>
                            <td>
                                <!--1 день - это тот же день, значит массив 0 => 1, 1 => 2 корректен-->
                                <?= Html::dropDownList('items_days', null, \app\models\BaseItems::getDaysPerItem(), [
                                    'id' => 'select_items_days',
                                    'class' => 'form-control input-sm',
                                    'prompt' => 'Дней в смене'
                                ]) ?>
                            </td>
                            <td>
                                <? $selection = ($base_items ? $base_items[0]->currency : Orders::CUR_RUB); ?>
                                <?= Html::dropDownList('currency', $selection, Orders::getCurrenciesFilter(), [
                                    'id' => 'select_items_currency',
                                    'class' => 'form-control input-sm',
                                ]) ?>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-success btn-add-items" title="Добавить смену">
                                    <i class="glyphicon glyphicon-plus"></i>
                                </button>
                            </td>
                        </tr>
                        <? if ($model->isNewRecord || empty($base_items)): ?>
                            <?= str_replace('{index}', 0, $this->render('camp-register-add-item', ['model_item' => $base_item, 'class' => $items_class])) ?>
                        <? else: ?>
                            <? foreach ($base_items AS $mk => $mi): ?>
                                <?= str_replace('{index}', $mk, $this->render('camp-register-add-item', ['model_item' => $mi, 'class' => $items_class])) ?>
                            <? endforeach; ?>
                        <? endif; ?>
                    </tbody>
                </table>
            </div>
    
            <div class="row m-t-20">
                <div class="col-xs-6">
                    <button class="btn btn-lg btn-block btn-default btn-steps btn-step-prev" type="button"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Назад</button>
                </div>
                <div class="col-xs-6">
                    <button class="btn btn-lg btn-block btn-primary btn-steps btn-step-next">Сохранить&nbsp;&nbsp;<i class="fa fa-ok"></i></button>
                </div>
            </div>
    
            <? ActiveForm::end(); ?>
        </div>
    </div>
</div>

<table id="tmp_base_item" class="hidden">
    <tbody><?= $this->render('camp-register-add-item', ['model_item' => $base_item, 'class' => $items_class]) ?></tbody>
</table>
<table id="tmp_base_period" class="hidden">
    <tbody><?= $this->render('camp-register-add-period', ['model_period' => $base_period, 'class' => $periods_class]) ?></tbody>
</table>
<div id="tmp_placement" class="hidden">
    <?= $this->render('camp-register-add-placement', ['model_placement' => $base_placement, 'class' => $placement_class]) ?>
</div>

<?php
$this->registerJs("
$(document).on('change', '.date-from-date', function(){    
    if (!this.value) return
    
    var ind = $(this).index('.date-from-date');
    
    var \$to_date = $('.date-to-date').eq(ind);
    var \$to_date_db = $('.date-to-date-db').eq(ind);
    
    var days_per_item = +$('#select_items_days').val();
    
    if (\$to_date.length && \$to_date.val() == '') {
    
        var dt = $(this).val().split('.');
        
        var d = new Date(dt[2], (+dt[1] - 1), dt[0]);
        d.setDate(d.getDate() + days_per_item);
         
        \$to_date.datepicker('setDate', d);
    }
});

// смена типа сезонности
$('#" . Html::getInputId($camp_contract, 'contract_period_type') . "').on('change', function(){
    var \$periods_block = $('.periods-block');
    if (this.value == '" . \app\models\CampsContract::PERIOD_ALWAYS . "') {
        \$periods_block.addClass('hidden').find(':input').prop('disabled', true);
    } else {
        \$periods_block.removeClass('hidden').find(':input').prop('disabled', false);
    }
}).change();

// добавление строки периода сезонности
var last_period_index = $('#base_periods .{$periods_class}').length;
$(document).on('click', '.btn-add-periods', function(){    
    var content = $('#tmp_base_period tbody').html().replace(/{index}/g, last_period_index);    
    $('#base_periods tbody').append(content);
    
    last_period_index++;
    
    var sel = $('#base_periods tbody tr:last').find('.datepickers');    
    set_datepickers(sel);
}).on('click', '.btn-delete-periods', function(){    
    if (confirm('Подтверждаете удаление периода?')) $(this).closest('tr').remove();
});

// добавление строки смены
var last_item_index = $('#base_items .{$items_class}').length;
$(document).on('click', '.btn-add-items', function(){
    var content = $('#tmp_base_item tbody').html().replace(/{index}/g, last_item_index);    
    $('#base_items tbody').append(content);
    
    last_item_index++;
    
    var sel = $('#base_items tbody tr:last').find('.datepickers');    
    set_datepickers(sel);
}).on('click', '.btn-delete-items', function(){    
    if (confirm('Подтверждаете удаление смены?')) $(this).closest('tr').remove();
});

// добавление строки типа размещения
var last_placement_index = $('#placement_items .{$placement_class}').length;
$(document).on('click', '.btn-add-placement', function(){
    var content = $('#tmp_placement').html().replace(/{index}/g, last_placement_index);    
    $('#placement_items').append(content);
    
    last_placement_index++;
}).on('click', '.btn-delete-placement', function(){    
    if (confirm('Подтверждаете удаление варианта размещения?')) $(this).closest('.{$placement_class}').remove();
});

$('#" . Html::getInputId($camp_about, 'loc_country') . "').on('change', function(){
    ajaxData(this, '#" . Html::getInputId($camp_about, 'loc_region') . "', '" . Url::to(['/ajax/options']) . "', {
        id: this.value,
        type: 'regions',
        first: '- Выбор региона -',
        empty: '- Регионы не найдены -'
    });
});

$('#" . Html::getInputId($camp_about, 'loc_region') . "').on('change', function(){
    ajaxData(this, '#" . Html::getInputId($camp_about, 'loc_city') . "', '" . Url::to(['/ajax/options']) . "', {
        id: this.value,
        type: 'cities',
        first: '- Выбор города -',
        empty: '- Города не найдены -'
    });
});

$('#" . Html::getInputId($camp_contract, 'opt_group_use') . "').on('ifChecked', function(){
    $('#" . Html::getInputId($camp_contract, 'opt_group_discount') . "').prop('disabled', false);
    $('#" . Html::getInputId($camp_contract, 'opt_group_count') . "').prop('disabled', false);
    $('#" . Html::getInputId($camp_contract, 'opt_group_guides') . "').prop('disabled', false);
}).on('ifUnchecked', function(){
    $('#" . Html::getInputId($camp_contract, 'opt_group_discount') . "').prop('disabled', true);
    $('#" . Html::getInputId($camp_contract, 'opt_group_count') . "').prop('disabled', true);
    $('#" . Html::getInputId($camp_contract, 'opt_group_guides') . "').prop('disabled', true);
});

// клик по навигации над формой
var \$steps = $('.camp-register-steps');
\$steps.on('click', function(e){
    e.preventDefault();
    
    var href = $(this).attr('href').replace('#', '');
    
    $(this).siblings().removeClass('active');
    $(this).addClass('active');
    
    $('.camp-register-forms').addClass('hidden');
    $('#form-' + href).removeClass('hidden');
    
    location.hash = href;
});

// отображение текущего щага из хеша
var \$cur_step = \$steps.filter('[href=\"' + location.hash + '\"]');
if (\$cur_step.length) \$cur_step.trigger('click');

var step_inc;
var \$btn_steps = $('.btn-steps');
\$btn_steps.on('mousedown', function(){
    if ($(this).hasClass('btn-step-prev')) {
        step_inc = -1;
        // переход на предыдущий шаг без отправки формы
        \$steps.filter('.active').prev().trigger('click');
    } else {
        step_inc = 1;
    }
});

$('.camp-register-forms form').on('beforeSubmit', function(){
    
    var \$f = $(this);
    var step = parseInt($('input[name=\"step\"]', \$f).val());
    
    var data = [];
    if (step == \$steps.length) {
        $('.camp-register-forms form').each(function(){
            data.push($(this).serialize());
        });
    } else {
        data.push(\$f.serialize());
    }
    
    $.ajax({
        url: '" . Yii::$app->request->url . "',
        data: data.join('&'),
        beforeSend: function(){            
            $('.form-group, .form-group-item', \$f).removeClass('has-error has-success');
            $('.form-group .help-block, .form-group-item .help-block', \$f).html('');
            //loader.show(\$f);
        },
        complete: null,
        success: function(resp){
        
            if (resp.redirect) {
                location.href = resp.redirect;
                return;
            }
            
            loader.hide();
            
            var step = parseInt(resp.step);
            var errors = resp.errors || null;
                
            if (errors) {
                for (var k in errors) {                    
                    if ($('#' + k).closest('.form-group-item').length) {
                        $('#' + k).closest('.form-group-item').addClass('has-error');
                        $('#' + k).closest('.form-group-item').find('.help-block').html(errors[k].join('<br />'));
                    } else {
                        $('#' + k).closest('.form-group').addClass('has-error');
                        $('#' + k).closest('.form-group').find('.help-block').html(errors[k].join('<br />'));
                    }
                }
                
                $('.camp-register-forms').addClass('hidden');
                $('#form-step-' + step).removeClass('hidden');
                
                $('.camp-register-steps').removeClass('active');
                $('.camp-register-step-' + step).addClass('active');
                
                var \$f = $('#form-step-' + step + ' .has-error:eq(0)');
                var pos_top = \$f.length ? \$f.offset().top - 80 : 0;
                scrollTo(pos_top);
                
            } else {
                $('.camp-register-forms').addClass('hidden');
                $('#form-step-' + (step + step_inc)).removeClass('hidden');
                
                $('.camp-register-steps').removeClass('active');
                $('.camp-register-step-' + (step + step_inc)).addClass('active');
                
                var pos_top = $('.camp-register-steps-container').offset().top - 80;
                scrollTo(pos_top);
            }
        }
    });
    
    return false;
});
");