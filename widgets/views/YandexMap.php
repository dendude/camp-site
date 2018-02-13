<?php
use app\models\forms\UploadForm;
use app\helpers\Normalize;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var $this View
 *
 * @var $model \yii\base\Model
 *
 * @var $field_lat string
 * @var $field_lng string
 * @var $field_zoom string
 *
 * @var $field_country string
 * @var $field_region string
 * @var $field_city string
 *
 * @var $field_watcher string
 *
 * @var $width string
 * @var $height string
 *
 * @var $hintContent string
 * @var $balloonContent string
 */

$this->registerJsFile('https://api-maps.yandex.ru/2.1/?lang=ru_RU', ['position' => View::POS_HEAD]);
$yandex_id = uniqid('ymaps_');
?>

<div id="<?= $yandex_id ?>" style="height: <?= $height ?>; width: <?= $width ?>"></div>
<?= Html::activeHiddenInput($model, $field_lat) ?>
<?= Html::activeHiddenInput($model, $field_lng) ?>
<?= Html::activeHiddenInput($model, $field_zoom) ?>

<script type="text/javascript">
    (function(ymaps) {
        var sel_lat = '#<?= Html::getInputId($model, $field_lat) ?>';
        var sel_lng = '#<?= Html::getInputId($model, $field_lng) ?>';
        var sel_zoom = '#<?= Html::getInputId($model, $field_zoom) ?>';
        var myMap, myPlacemark;
        
        ymaps.ready(init);
            
        function init() {
            /** default moscow */
            myMap = new ymaps.Map("<?= $yandex_id ?>", {
                controls: ['zoomControl', 'rulerControl'],
                center: [$(sel_lat).val(), $(sel_lng).val()],
                zoom: $(sel_zoom).val()
            });
        
            myMap.events.add('boundschange', function (event) {
                if (event.get('newZoom') != event.get('oldZoom')) {
                    set_zoom(event.get('newZoom'));
                }
            });
        
            // создаем метку
            myPlacemark = new ymaps.Placemark(myMap.getCenter(), {
                hintContent: '<?= $hintContent ?>',
                balloonContent: '<?= $balloonContent ?>'
            }, {
                draggable: true
            });
            myMap.geoObjects.add(myPlacemark);
        
            // ослеживаем перемещение метки мышкой
            myPlacemark.events.add('dragend', function (e) {
                set_coords(this.geometry.getCoordinates());
            }, myPlacemark);
        
            // элемент управления без метки в результате поиска
            var searchControl = new ymaps.control.SearchControl({
                options: {
                    float: 'right',
                    noPlacemark: true,
                    maxWidth: [100, 200, 450]
                }
            });
            myMap.controls.add(searchControl);
        
            // в точку результата поиска ставим нашу метку
            searchControl.events.add("resultselect", function (e) {
                var coords = searchControl.getResultsArray()[0].geometry.getCoordinates();
                myPlacemark.geometry.setCoordinates(coords);
                set_coords(coords);
            });
            
            <? if ($field_watcher): ?>
            $('#<?= Html::getInputId($model, $field_watcher) ?>').on('change', function(){
                watcher(this.value);
            });
            var timer_map;
            $('#<?= Html::getInputId($model, $field_watcher) ?>').on('keyup', function(){
                clearTimeout(timer_map);
                
                var $t = $(this);
                timer_map = setTimeout(function(){
                    watcher($t.val());
                }, 3000);
            });
            <? endif; ?>
        }
        
        function watcher(t) {
            <? if ($field_country && $field_region && $field_city): ?>
            var country = $('#<?= Html::getInputId($model, $field_country) ?> option:selected').text(),
                region = $('#<?= Html::getInputId($model, $field_region) ?> option:selected').text(),
                city = $('#<?= Html::getInputId($model, $field_city) ?> option:selected').text();
            t = country + ' ' + region + ' ' + city + ' ' + t;
            <? endif; ?>
            
            var myGeocoder = ymaps.geocode(t);
            myGeocoder.then(
                function (res) {
                    var MyGeoObj = res.geoObjects.get(0);
                    var coords = MyGeoObj.geometry.getCoordinates();
                    var bounds = MyGeoObj.properties.get('boundedBy');

                    myPlacemark.geometry.setCoordinates(coords);
                    
                    myMap.setBounds(bounds, {
                        // Масштабируем карту на область видимости геообъекта.
                        // Проверяем наличие тайлов на данном масштабе.
                        checkZoomRange: true
                    });

                    $('#<?= Html::getInputId($model, $field_lat) ?>').val(coords[0]);
                    $('#<?= Html::getInputId($model, $field_lng) ?>').val(coords[1]);
                },
                function (err) {
                    alert('Ошибка определения адреса на карте! Вам необходимо вручную отметить метку на карте или изменить текст адреса.');
                }
            );
        }
    
        function set_coords(coords) {
            $(sel_lat).val(coords[0]);
            $(sel_lng).val(coords[1]);
        }
    
        function set_zoom(zoom) {
            $(sel_zoom).val(zoom);
        }
    })(ymaps);
</script>