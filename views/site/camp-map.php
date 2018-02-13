<?
use yii\helpers\Html;

/**
 * @var $model \app\models\Camps
 */
$map_id = uniqid('ymap_');
?>
<div id="<?= $map_id ?>" style="min-height: 100%;"></div>
<script type="text/javascript">
    ymaps.ready(init);
    
    function init(){
        var myMap = new ymaps.Map("<?= $map_id ?>", {
            zoom: 13,
            center: [<?= $model->about->loc_coords_f['lat'] ?>, <?= $model->about->loc_coords_f['lng'] ?>],
            controls: ["default"]
        });
        
        // создаем метку
        var myPlacemark = new ymaps.Placemark(myMap.getCenter(), {
            hintContent: '<?= Html::encode($model->about->name_short) ?>',
            balloonContent: '<?= Html::encode($model->about->loc_address) ?>'
        });
        myMap.geoObjects.add(myPlacemark);
    }
</script>