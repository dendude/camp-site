<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\TagsPlaces;
use app\models\TagsTypes;
use app\models\TagsSport;
use app\models\LocCountries;
use app\models\LocRegions;
use app\models\LocCities;
use yii\helpers\Url;
use app\models\CampsContract;
use app\modules\manage\widgets\DropZoneWidget;
use app\models\Orders;
use app\widgets\FroalaSimpleEditorWidget;

/**
 * @var $this \yii\web\View
 *
 * @var $model \app\models\Camps
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
 * @var $camp_contract CampsContract
 * @var $camp_contacts \app\models\CampsContacts
 */

$action = $model->id ? 'Редактирование лагеря' : 'Добавление лагеря';
$this->title = $action;
$this->params['breadcrumbs'] = [
    ['label' => \app\modules\manage\controllers\CampsController::LIST_NAME, 'url' => ['list']],
    ['label' => $action]
];

$w100 = ['inputOptions' => ['class' => 'form-control w-100']];
$w200 = ['inputOptions' => ['class' => 'form-control w-200']];

if ($model->id) $this->registerJs("$('textarea').trigger('keyup');");

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
        'template' => '<div class="col-xs-12 col-lg-4 text-right">{label}</div><div class="col-xs-12 col-lg-8">{input}{error}</div>',
        'labelOptions' => ['class' => 'control-label']
    ]
];
?>
<div class="camp-register-forms">
    <div class="text-right p-b-15"><?= Yii::$app->params['required_fields'] ?></div>
    
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a id="tab_step1" data-toggle="tab" href="#step1" class="tabs">О лагере</a></li>
            <li><a id="tab_step2" data-toggle="tab" href="#step2" class="tabs">Размещение</a></li>
            <li><a id="tab_step3" data-toggle="tab" href="#step3" class="tabs">Фото и видео</a></li>
            <li><a id="tab_step4" data-toggle="tab" href="#step4" class="tabs">Дополнительно</a></li>
            <li><a id="tab_step5" data-toggle="tab" href="#step5" class="tabs">Контакты</a></li>
            <li><a id="tab_step6" data-toggle="tab" href="#step6" class="tabs">Договор и скидки</a></li>
            <li><a id="tab_step7" data-toggle="tab" href="#step7" class="tabs">Смены и цены</a></li>
            <li><a id="tab_step8" data-toggle="tab" href="#step8" class="tabs">Управление лагерем</a></li>
        </ul>
        <div class="tab-content" style="background: rgba(0,0,0,0.02)">
            
            <div id="step1" class="camp-register-steps tab-pane p-t-20 active">
                <? $form = ActiveForm::begin($form_config); ?>
                <?= Html::hiddenInput('step', 1); ?>
    
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
                                            <p class="p-t-7">&nbsp;&nbsp;&ndash;&nbsp;&nbsp;</p>
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

                        <div class="separator"></div>
            
                        <?= $form->field($camp_about, 'made_year', [
                            'template' => str_replace(['{addon}','{class}'], ['году','w-150'], Yii::$app->params['group_template'])
                        ])->input('number', ['min' => 1900, 'max' => date('Y'), 'step' => 1]) ?>
            
                        <?= $form->field($camp_about, 'count_builds', $w150)->input('number', ['min' => 1, 'max' => 100, 'step' => 1]) ?>
            
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
                        <?= $form->field($camp_about, 'loc_country', [
                            'template' => str_replace([
                                '{url}', '{refresh}'
                            ], [
                                Url::to(['loc-countries/add']), 'update_countries()'
                            ], Yii::$app->params['add_template'])
                        ])->dropDownList(LocCountries::getFilterList(true), [
                            'class' => 'form-control custom-select',
                            'prompt' => '- Выбор страны -'
                        ]) ?>
                        <?= $form->field($camp_about, 'loc_region', [
                            'template' => str_replace([
                                '{url}', '{refresh}'
                            ], [
                                Url::to(['loc-regions/add']), 'update_regions()'
                            ], Yii::$app->params['add_template'])
                        ])->dropDownList(LocRegions::getFilterList($camp_about->loc_country, true), [
                            'class' => 'form-control custom-select',
                        ]) ?>
                        <?= $form->field($camp_about, 'loc_city', [
                            'template' => str_replace([
                                '{url}', '{refresh}'
                            ], [
                                Url::to(['loc-cities/add']), 'update_cities()'
                            ], Yii::$app->params['add_template'])
                        ])->dropDownList(LocCities::getFilterList($camp_about->loc_region, true), [
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
                        ]) ?>
                        
                        <?php
                        $this->registerJs("
                            $('#" . Html::getInputId($camp_about, 'trans_escort_cities_f') . "').val([" . implode(',', array_keys($camp_about->trans_escort_cities_f)) . "]).trigger('change');
                        ");
                        ?>
                        
                        <?= $form->field($camp_about, 'loc_address')->textInput(['placeholder' => 'Без указания города, только адрес']) ?>
                        
                        <div class="form-group">
                            <div class="col-xs-12 col-md-offset-4 col-md-7">
                                <button type="button" class="btn btn-link" onclick="toggle_map('#loc_address')"><?= $camp_about->getAttributeLabel('loc_coords') ?></button>
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

                                    'field_watcher' => 'loc_address',

                                    'hintContent' => Html::encode($camp_about->name_short),
                                    'balloonContent' => Html::encode($camp_about->name_full) . '<br/>' . Html::encode($camp_about->loc_address)
                                ]) ?>
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
                        <?= $form->field($camp_about, 'trans_in_price')->checkbox(['class' => 'ichecks']) ?>
                        <?= $form->field($camp_about, 'trans_with_escort')->checkbox(['class' => 'ichecks']) ?>
                    </div>
                </div>
    
                <div class="line-separator m-t-5 m-b-25"></div>
    
                <div class="form-group">
                    <div class="col-xs-12 col-md-2 text-right">
                        <label>
                            <?= $camp_about->getAttributeLabel('tags_types') ?>
                            <?= $camp_about->isAttributeRequired('tags_types') ? '<span class="required">*</span>' : '' ?>
                        </label>
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
    
                <div class="row m-t-20">
                    <div class="col-xs-6"></div>
                    <div class="col-xs-6">
                        <button class="btn btn-lg btn-block btn-primary btn-steps btn-step-next">Далее&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
                    </div>
                </div>
    
                <? ActiveForm::end(); ?>
            </div>
            
            <div id="step2" class="camp-register-steps tab-pane p-t-20">
                <? $form = ActiveForm::begin($form_config); ?>
                <?= Html::hiddenInput('step', 2); ?>
    
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
                                    <?= str_replace('{index}', 0, $this->render('//site/camp-register-add-placement', ['model_placement' => $base_placement, 'class' => $placement_class])) ?>
                                <? else: ?>
                                    <? foreach ($base_placements AS $mk => $mi): ?>
                                        <?= str_replace('{index}', $mk, $this->render('//site/camp-register-add-placement', ['model_placement' => $mi, 'class' => $placement_class])) ?>
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
    
                <div class="row m-t-20">
                    <div class="col-xs-6">
                        <button class="btn btn-lg btn-block btn-default btn-steps btn-step-prev"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Назад</button>
                    </div>
                    <div class="col-xs-6">
                        <button class="btn btn-lg btn-block btn-primary btn-steps btn-step-next">Далее&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
                    </div>
                </div>
    
                <? ActiveForm::end(); ?>
            </div>
            
            <div id="step3" class="camp-register-steps tab-pane p-t-20">
                <? $form = ActiveForm::begin($form_config); ?>
                <?= Html::hiddenInput('step', 3); ?>
                
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group <?= $camp_media->isAttributeRequired('photo_order_free') ? 'required' : '' ?>">
                            <label class="control-label text-left col-xs-12 p-b-5" for="<?= Html::getInputId($camp_media, 'photo_order_free') ?>">
                                <?= $camp_media->getAttributeLabel('photo_order_free') ?> (на главной странице)
                            </label>
                            <div class="col-xs-12">
                                <?= DropZoneWidget::widget([
                                    'zone_id' => Html::getInputId($camp_media, 'photo_order_free'),
                                    'model' => $camp_media,
                                    'field' => 'photo_order_free',
                                    'url' => Url::to(['/ajax/upload']),
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group <?= $camp_media->isAttributeRequired('photo_main') ? 'required' : '' ?>">
                            <label class="control-label text-left col-xs-12 p-b-5" for="<?= Html::getInputId($camp_media, 'photo_main') ?>">
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
                            <label class="control-label text-left col-xs-12 p-b-5" for="<?= Html::getInputId($camp_media, 'photo_partner') ?>">
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
                            <label class="control-label text-left col-xs-12 p-b-5" for="<?= Html::getInputId($camp_media, 'photos_room_f') ?>">
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
                            <label class="control-label text-left col-xs-12 p-b-5" for="<?= Html::getInputId($camp_media, 'photos_sport_f') ?>">
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
                            <label class="control-label text-left col-xs-12 p-b-5" for="<?= Html::getInputId($camp_media, 'photos_area_f') ?>">
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
                            <label class="control-label text-left col-xs-12 p-b-5" for="<?= Html::getInputId($camp_media, 'photos_eating_f') ?>">
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
                            <label class="control-label text-left col-xs-12 p-b-5" for="<?= Html::getInputId($camp_media, 'photos_comfort_f') ?>">
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
                            <label class="control-label text-left col-xs-12 p-b-5" for="<?= Html::getInputId($camp_media, 'photos_med_f') ?>">
                                <?= $camp_media->getAttributeLabel('photos_med_f') ?> (от 1 до 3-х фотографий)
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
                            <label class="control-label text-left col-xs-12 p-b-5" for="<?= Html::getInputId($camp_media, 'photos_security_f') ?>">
                                <?= $camp_media->getAttributeLabel('photos_security_f') ?> (от 1 до 3-х фотографий)
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
                            <label class="control-label text-left col-xs-12 p-b-5" for="<?= Html::getInputId($camp_media, 'photos_concert_hall_f') ?>">
                                <?= $camp_media->getAttributeLabel('photos_concert_hall_f') ?> (от 1 до 3-х фотографий)
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
                    <label class="control-label text-left col-xs-12 p-b-5" for="<?= Html::getInputId($camp_media, 'photos_others_f') ?>">
                        <?= $camp_media->getAttributeLabel('photos_others_f') ?> (не более 10-и фотографий)
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
    
                <div class="row m-t-20">
                    <div class="col-xs-6">
                        <button class="btn btn-lg btn-block btn-default btn-steps btn-step-prev"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Назад</button>
                    </div>
                    <div class="col-xs-6">
                        <button class="btn btn-lg btn-block btn-primary btn-steps btn-step-next">Далее&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
                    </div>
                </div>
    
                <? ActiveForm::end(); ?>
            </div>
    
            <div id="step4" class="camp-register-steps tab-pane p-t-20">
                <? $form = ActiveForm::begin($form_config); ?>
                <?= Html::hiddenInput('step', 4); ?>
    
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
    
                <div class="row m-t-20">
                    <div class="col-xs-6">
                        <button class="btn btn-lg btn-block btn-default btn-steps btn-step-prev"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Назад</button>
                    </div>
                    <div class="col-xs-6">
                        <button class="btn btn-lg btn-block btn-primary btn-steps btn-step-next">Далее&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
                    </div>
                </div>
    
                <? ActiveForm::end(); ?>
            </div>
    
            <div id="step5" class="camp-register-steps tab-pane p-t-20">
                <? $form = ActiveForm::begin($form_config); ?>
                <?= Html::hiddenInput('step', 5); ?>
    
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
                                <button type="button" class="btn btn-link" onclick="toggle_map('#cont_address')"><?= $camp_contacts->getAttributeLabel('office_coords') ?></button>
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
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <?= $form->field($camp_contacts, 'office_route')->textarea([
                            'placeholder' => 'Как проехать к офису и график работы',
                            'class' => 'form-control h-142'
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
    
                <div class="row m-t-20">
                    <div class="col-xs-6">
                        <button class="btn btn-lg btn-block btn-default btn-steps btn-step-prev"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Назад</button>
                    </div>
                    <div class="col-xs-6">
                        <button class="btn btn-lg btn-block btn-primary btn-steps btn-step-next">Далее&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
                    </div>
                </div>
    
                <? ActiveForm::end(); ?>
            </div>
    
            <div id="step6" class="camp-register-steps tab-pane p-t-20">
                <? $form = ActiveForm::begin($form_config); ?>
                <?= Html::hiddenInput('step', 6); ?>

                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <div class="well well-sm p-t-20">
                            <?= $form->field($camp_contract, 'contract_date_f', $dtpk) ?>
                            <?= $form->field($camp_contract, 'contract_number', $w200)->textInput(['maxlength' => true]) ?>

                            <?= $form->field($camp_contract, 'contract_comission_type', $w200)->dropDownList(CampsContract::getComissionTypes(), ['prompt' => '']) ?>
                            <?= $form->field($camp_contract, 'contract_comission', [
                                'template' => str_replace(['{addon}','{class}'], ['%','w-200 comission-addon'], Yii::$app->params['group_template']),
                            ])->textInput(['maxlength' => true]) ?>
    
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
                                            <?= str_replace('{index}', 0, $this->render('add_period', ['model_period' => $base_period, 'class' => $periods_class])) ?>
                                        <? else: ?>
                                            <? foreach ($base_periods AS $mk => $mp): ?>
                                                <?= str_replace('{index}', $mk, $this->render('add_period', ['model_period' => $mp, 'class' => $periods_class])) ?>
                                            <? endforeach; ?>
                                        <? endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="p-t-7">
                            <?= $form->field($camp_contract, 'opt_use_paytravel', $chbx)->checkbox(['class' => 'ichecks', 'uncheck' => null]) ?>
                        </div>
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

                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <?= $form->field($camp_contract, 'contract_inn')->textInput(['maxlength' => true]) ?>
                        <?= $form->field($camp_contract, 'contract_ogrn_serial')->textInput(['maxlength' => true]) ?>
                        <?= $form->field($camp_contract, 'contract_ogrn_number')->textInput(['maxlength' => true]) ?>
                        <?= $form->field($camp_contract, 'contract_ogrn_date_f', $dtpk)->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        
                    </div>
                </div>
    
                <div class="row m-t-20">
                    <div class="col-xs-6">
                        <button class="btn btn-lg btn-block btn-default btn-steps btn-step-prev"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Назад</button>
                    </div>
                    <div class="col-xs-6">
                        <button class="btn btn-lg btn-block btn-primary btn-steps btn-step-next">Далее&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
                    </div>
                </div>
    
                <? ActiveForm::end(); ?>
            </div>
    
            <div id="step7" class="camp-register-steps tab-pane p-t-20">
                <? $form = ActiveForm::begin($form_config); ?>
                <?= Html::hiddenInput('step', 7); ?>
                
                <table id="base_items" class="table table-condensed">
                    <thead>
                        <tr>
                            <th class="text-left">Короткое и полное<br>названия смены</th>
                            <th width="150">Диапазон дат<br>для смены</th>
                            <th width="150">Кол-во путевок<br>и цена партнера</th>
                            <th class="text-warning" width="150">Тип комиссии<br>и значение</th>
                            <th class="text-success" width="150">Тип скидки<br>и значение</th>
                            <th width="150">Статус</th>
                            <th width="50">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="<?= $items_class ?>">
                            <td></td>
                            <td>
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
                            <td class="text-center small text-muted" style="line-height: 13px;">
                                * Комиссия входит<br>в цену партнера
                            </td>
                            <td class="text-center small text-muted" style="line-height: 13px;">
                                ** Скидка считается<br>от комиссии
                            </td>
                            <td></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-success btn-add-items" title="Добавить смену">
                                    <i class="glyphicon glyphicon-plus"></i>
                                </button>
                            </td>
                        </tr>
                        <? if ($model->isNewRecord || empty($base_items)): ?>
                            <?= str_replace('{index}', 0, $this->render('add_item', ['model_item' => $base_item, 'class' => $items_class])) ?>
                        <? else: ?>
                            <? foreach ($base_items AS $mk => $mi): ?>
                                <?= str_replace('{index}', $mk, $this->render('add_item', ['model_item' => $mi, 'class' => $items_class])) ?>
                            <? endforeach; ?>
                        <? endif; ?>
                    </tbody>
                </table>
    
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
    
            <div id="step8" class="camp-register-steps tab-pane p-t-20">
                <? $form = ActiveForm::begin($form_config); ?>
                <?= Html::hiddenInput('step', 8); ?>
    
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <?= $form->field($model, 'meta_t')->textarea() ?>
                        <?= $form->field($model, 'meta_d')->textarea() ?>
                        <?= $form->field($model, 'meta_k')->textarea() ?>
                        <div class="separator"></div>
                        <?= $form->field($model, 'ordering', $w100)->input('number', ['step' => 1]) ?>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <?= $form->field($model, 'status', $chbx)->checkbox(['class' => 'ichecks', 'label' => 'Опубликовать на сайте [активировать]']) ?>
                        <div class="separator"></div>
                        <?= $form->field($model, 'is_main', $chbx)->checkbox(['class' => 'ichecks', 'label' => 'Выводить на главной с меткой Забронировать бесплатно']) ?>
                        <?= $form->field($model, 'is_rating', $chbx)->checkbox(['class' => 'ichecks', 'label' => 'Выводить на главной в Рейтинге по отзывам']) ?>
                        <?/*= $form->field($model, 'is_recommend', $chbx)->checkbox(['class' => 'ichecks', 'label' => 'Выводить в Подборке детских лагерей']) */?>
                        <div class="separator"></div>
                        <?= $form->field($model, 'is_new', $chbx)->checkbox(['class' => 'ichecks', 'label' => 'Пометка "Новый лагерь"']) ?>
                        <?= $form->field($model, 'is_vip', $chbx)->checkbox(['class' => 'ichecks', 'label' => 'Пометка "VIP"']) ?>
                        <?= $form->field($model, 'is_leader', $chbx)->checkbox(['class' => 'ichecks', 'label' => 'Пометка "Лидер продаж"']) ?>
                    </div>
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
    
</div>

<table id="tmp_base_item" class="hidden">
    <tbody><?= $this->render('add_item', ['model_item' => $base_item, 'class' => $items_class]) ?></tbody>
</table>
<table id="tmp_base_period" class="hidden">
    <tbody><?= $this->render('add_period', ['model_period' => $base_period, 'class' => $periods_class]) ?></tbody>
</table>
<div id="tmp_placement" class="hidden">
    <?= $this->render('//site/camp-register-add-placement', ['model_placement' => $base_placement, 'class' => $placement_class]) ?>
</div>

<script type="text/javascript">
    var sel_country = '#<?= Html::getInputId($camp_about, 'loc_country') ?>';
    var sel_region = '#<?= Html::getInputId($camp_about, 'loc_region') ?>';
    var sel_city = '#<?= Html::getInputId($camp_about, 'loc_city') ?>';
    
    // обновление списка стран
    function update_countries() {
        loader.show($(sel_country).closest('div'));
        ajaxData(sel_country, sel_country, '<?= Url::to(['/ajax/options']) ?>', {
            type: 'countries',
            first: '- Выбор страны -',
            empty: '- Страны не найдены -',
            selected: $(sel_country).val(),
            full: true
        });
    }

    // обновление списка регионов
    function update_regions() {
        loader.show($(sel_region).closest('div'));
        $(sel_country).change();
    }

    // обновление списка городов
    function update_cities() {
        loader.show($(sel_city).closest('div'));
        $(sel_region).change();
    }
</script>

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
    if (this.value == '" . CampsContract::PERIOD_ALWAYS . "') {
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
    var sel_region = '#" . Html::getInputId($camp_about, 'loc_region') . "';
    
    ajaxData(this, sel_region, '" . Url::to(['/ajax/options']) . "', {
        id: this.value,
        type: 'regions',
        first: '- Выбор региона -',
        empty: '- Регионы не найдены -',
        selected: $(sel_region).val(),
        full: true
    });
});

$('#" . Html::getInputId($camp_about, 'loc_region') . "').on('change', function(){
    var sel_city = '#" . Html::getInputId($camp_about, 'loc_city') . "';

    ajaxData(this, sel_city, '" . Url::to(['/ajax/options']) . "', {
        id: this.value,
        type: 'cities',
        first: '- Выбор города -',
        empty: '- Города не найдены -',
        selected: $(sel_city).val(),
        full: true
    });
});

// смена типа комиссии
$('#" . Html::getInputId($camp_contract, 'contract_comission_type') . "').on('change', function(){
    if (this.value == '" . CampsContract::COMISSION_SUM . "') {
        $('.comission-addon .input-group-addon').text('" . Orders::CUR_RUB . "');
        $('#" . Html::getInputId($camp_contract, 'contract_comission') . "').attr('maxlength', 10);
    } else {
        $('.comission-addon .input-group-addon').text('%');
        $('#" . Html::getInputId($camp_contract, 'contract_comission') . "').attr('maxlength', 2);
    }
}).trigger('change');

// есть ли групповая скидка
$('#" . Html::getInputId($camp_contract, 'opt_group_use') . "').on('ifChecked', function(){
    $('#" . Html::getInputId($camp_contract, 'opt_group_discount') . "').prop('disabled', false);
    $('#" . Html::getInputId($camp_contract, 'opt_group_count') . "').prop('disabled', false);
    $('#" . Html::getInputId($camp_contract, 'opt_group_guides') . "').prop('disabled', false);
}).on('ifUnchecked', function(){
    $('#" . Html::getInputId($camp_contract, 'opt_group_discount') . "').prop('disabled', true);
    $('#" . Html::getInputId($camp_contract, 'opt_group_count') . "').prop('disabled', true);
    $('#" . Html::getInputId($camp_contract, 'opt_group_guides') . "').prop('disabled', true);
});

// в какую сторону происходит переход
var \$steps = $('.camp-register-steps');
var \$btn_steps = $('.btn-steps');
var step_inc;
\$btn_steps.on('mousedown', function(){
    if ($(this).hasClass('btn-step-prev')) {
        step_inc = -1;
    } else {
        step_inc = 1;
    }
});

// сохраняем в хеш клик по табу
$('.tabs').on('mousedown', function(){
    location.hash = $(this).attr('href').replace('#','');
});

// отображаем текущую вкладку после обновления страницы
var current_hash = location.hash.replace('#','');
if (current_hash) {
    $('a.tabs[href=\"#' + current_hash + '\"]').click();
}

// отправка формы на валидацию
$('.camp-register-forms form').on('beforeSubmit', function(){
    
    var \$f = $(this); 
    var step = parseInt($('input[name=\"step\"]', \$f).val());
    
    var data = [];
    $('.camp-register-forms form').each(function(){
        data.push($(this).serialize());
    });    
    data.push('step=' + step);
    
    $.ajax({
        url: '" . Yii::$app->request->url . "',
        data: data.join('&'),
        beforeSend: function(){
            $('.form-group, .form-group-item', \$f).removeClass('has-error has-success');
            $('.form-group .help-block, .form-group-item .help-block', \$f).html('');
            loader.show(\$f);
        },
        complete: null,
        success: function(resp){            
            if (resp.redirect) {                
                location.href = resp.redirect;
            } else if (resp.length == 0) {
                loader.hide();
                if ((step + step_inc) <= \$steps.length) {
                    $('#tab_step' + (step + step_inc)).click();
                    $('html').animate({scrollTop: 0});
                }
            } else {
                loader.hide();                
                for (var k in resp) {                    
                    if (k == 'step') continue;
                    
                    if ($('#' + k).closest('.form-group-item').length) {
                        $('#' + k).closest('.form-group-item').addClass('has-error');
                        $('#' + k).closest('.form-group-item').find('.help-block').html(resp[k].join('<br />'));
                    } else {
                        $('#' + k).closest('.form-group').addClass('has-error');
                        $('#' + k).closest('.form-group').find('.help-block').html(resp[k].join('<br />'));
                    }
                }
                
                $('#tab_step' + resp.step).click();
                $('html').animate({
                    scrollTop: $('.has-error:eq(0)').offset().top - 70
                });
            }
        }
    });
    
    return false;
});
");