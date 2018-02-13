<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\LocCountries;
use app\models\LocRegions;
use app\models\forms\SearchForm;
use app\models\TagsTypes;
use app\models\Camps;

/**
 * @var $model SearchForm
 * @var $type string
 */
?>

<? $form = ActiveForm::begin([
    'action' => Url::to(['/camps']),
    'method' => 'GET',
]); ?>

<? if ($type == \app\widgets\CampSearch::TYPE_COLUMN): ?>
    
    <div class="page-search">
        <div class="m-b-15">
            <?= Html::activeDropDownList($model, 'country_id', LocCountries::getFilterListWithCamps(), [
                'class' => 'form-control custom-select',
                'prompt' => '- Страна -'
            ]) ?>
        </div>
        <div class="m-b-15">
            <?= Html::activeDropDownList($model, 'region_id', LocRegions::getFilterListWithCamps($model->country_id), [
                'class' => 'form-control custom-select region-select',
                'prompt' => '- Регион -'
            ]) ?>
        </div>
        <div class="m-b-15">
            <?= Html::activeDropDownList($model, 'date', SearchForm::getDates(), [
                'class' => 'form-control custom-select',
                'prompt' => '- Смена -'
            ]) ?>
        </div>
        <div class="m-b-15">
            <?= Html::activeDropDownList($model, 'ages', SearchForm::getAges(), [
                'class' => 'form-control custom-select',
                'prompt' => '- Возраст -'
            ]) ?>
        </div>
        <div class="m-b-15">
            <?= Html::activeDropDownList($model, 'type', TagsTypes::getFilterListWithCamps($model->country_id, $model->region_id), [
                'class' => 'form-control custom-select type-select',
                'prompt' => '- Тип лагеря -'
            ]) ?>
        </div>
        <div class="m-b-25">
            <?= Html::activeTextInput($model, 'name', [
                'class' => 'form-control',
                'placeholder' => 'Название лагеря'
            ]) ?>
        </div>
        <button type="submit" class="btn btn-block btn-link page-submit">
            <i class="fa fa-search"></i>Найти
        </button>
    </div>
    
<? else: ?>
    
    <div class="camp-search">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="29%" class="p-4">
                    <?= Html::activeDropDownList($model, 'country_id', LocCountries::getFilterListWithCamps(), [
                        'class' => 'form-control custom-select',
                        'prompt' => '- Все страны -',
                    ]) ?>
                </td>
                <td width="29%" class="p-4">
                    <?= Html::activeDropDownList($model, 'region_id', LocRegions::getFilterListWithCamps($model->country_id), [
                        'class' => 'form-control custom-select region-select',
                        'prompt' => '- Все регионы -'
                    ]) ?>
                </td>
                <td width="29%" class="p-4">
                    <?= Html::activeDropDownList($model, 'date', SearchForm::getDates(), [
                        'class' => 'form-control custom-select',
                        'prompt' => '- Смена/Сезон -'
                    ]) ?>
                </td>
                <td rowspan="2" class="hidden-xs hidden-sm">
                    <button class="camp-search-submit">
                        <i class="fa fa-search"></i>Найти
                    </button>
                </td>
            </tr>
            <tr>
                <td class="p-4">
                    <?= Html::activeDropDownList($model, 'ages', SearchForm::getAges(), [
                        'class' => 'form-control custom-select',
                        'prompt' => '- Любой возраст -'
                    ]) ?>
                </td>
                <td class="p-4">
                    <?= Html::activeDropDownList($model, 'type', TagsTypes::getFilterListWithCamps($model->country_id, $model->region_id), [
                        'class' => 'form-control custom-select type-select',
                        'prompt' => '- Тип лагеря -'
                    ]) ?>
                </td>
                <td class="p-4">
                    <?= Html::activeTextInput($model, 'name', [
                        'class' => 'form-control',
                        'placeholder' => 'Название лагеря'
                    ]) ?>
                </td>
            </tr>
            <tr class="hidden visible-xs visible-sm">
                <td class="submit-mobile" colspan="3">
                    <button class="camp-search-submit">
                        <i class="fa fa-search"></i>Найти
                    </button>
                </td>
            </tr>
        </table>
    </div>
    
<? endif; ?>
<? ActiveForm::end(); ?>

<script>
    var countries_aliases = [];
    <? foreach (LocCountries::find()->all() AS $m): ?>
    countries_aliases[<?= $m->id ?>] = '<?= $m->alias ?>';
    <? endforeach; ?>

    var regions_aliases = [];
    <? foreach (LocRegions::find()->all() AS $m): ?>
    regions_aliases[<?= $m->id ?>] = '<?= $m->alias ?>';
    <? endforeach; ?>

    var types_aliases = [];
    <? foreach (TagsTypes::find()->all() AS $m): ?>
    types_aliases[<?= $m->id ?>] = '<?= $m->alias ?>';
    <? endforeach; ?>
</script>
<?php
$this->registerJs("
$('#" . $form->getId() . "').on('submit', function(e){
    e.preventDefault();
    
    var country_id = $('#" . Html::getInputId($model, 'country_id') . "').val();
    var region_id = $('#" . Html::getInputId($model, 'region_id') . "').val();
    var type_id = $('#" . Html::getInputId($model, 'type') . "').val();
    
    var send_str = '/camps.html', 
        send_arr = [],
        send_opt = [];
        
    if (country_id) send_arr.push(countries_aliases[country_id]);
    if (region_id) send_arr.push(regions_aliases[region_id]);
    if (type_id) send_arr.push(types_aliases[type_id]);
    
    if (send_arr.length) {
        if (send_arr.length == 1) {
            if (country_id) send_str = '/camps/country/' + countries_aliases[country_id] + '.html';
            if (region_id)  send_str = '/camps/region/' + regions_aliases[region_id] + '.html';
            if (type_id)    send_str = '/camps/type/' + types_aliases[type_id] + '.html';
        } else {
            send_str = '/camps/' + send_arr.join('--') + '.html';
        }
    }
    
    var ages_id = $('#" . Html::getInputId($model, 'ages') . "').val();
    var name_id = $('#" . Html::getInputId($model, 'name') . "').val();
    var date_id = $('#" . Html::getInputId($model, 'date') . "').val();
    
    if (ages_id) send_opt.push('ages=' + ages_id);
    if (name_id) send_opt.push('name=' + name_id);
    if (date_id) send_opt.push('date=' + date_id);
    
    if (send_opt.length) {
        send_str += '?' + send_opt.join('&');
    }

    // перенаправляем
    location.href = send_str;
});

var \$select_filters = $('.page-filters select');
\$select_filters.on('change', function(){
    $(this).find('option').addClass('text-muted').removeClass('bold');
    $(this).find('option:selected').removeClass('text-muted').addClass('bold');
    
    if ($(this).val()) {
        $(this).addClass('bold');
    } else {
        $(this).removeClass('bold');
    }
}).change();

$('#" . Html::getInputId($model, 'country_id') . "').on('change', function(){
    var country_id = this.value;
    
    ajaxData(this, '.region-select', '" . Url::to(['/ajax/options']) . "', {
        id: country_id,
        type: 'regions',
        has_camps: true,
        first: '- Выбор региона -',
        empty: '- Регионы не найдены -'
    });
    
    ajaxData(this, '.type-select', '" . Url::to(['/ajax/options']) . "', {
        country_id: country_id,
        type: 'types',        
        has_camps: true,
        first: '- Тип лагеря -',
        empty: '- Типы не найдены -'
    });
    
    $('.type-select').removeClass('bold');
});

$('#" . Html::getInputId($model, 'region_id') . "').on('change', function(){
    var region_id = this.value;

    ajaxData(this, '.type-select', '" . Url::to(['/ajax/options']) . "', {
        region_id: region_id,
        type: 'types',
        has_camps: true,
        first: '- Тип лагеря -',
        empty: '- Типы не найдены -'
    });
    
    $('.type-select').removeClass('bold');
});
");