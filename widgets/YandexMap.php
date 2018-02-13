<?php
namespace app\widgets;

use Yii;
use yii\base\Widget;

class YandexMap extends Widget {
        
    public $model;
    
    public $width = '100%';
    public $height = '500px';
    
    public $field_lat;
    public $field_lng;
    public $field_zoom;

    public $field_country;
    public $field_region;
    public $field_city;
    
    public $field_watcher = '';
    
    public $hintContent = '';
    public $balloonContent = '';
    
	public function run() {
		return $this->render('YandexMap', [
		    'model' => $this->model,
            
            'width' => $this->width,
            'height' => $this->height,
            
            'field_lat' => $this->field_lat,
            'field_lng' => $this->field_lng,
            'field_zoom' => $this->field_zoom,
        
            'field_country' => $this->field_country,
            'field_region' => $this->field_region,
            'field_city' => $this->field_city,
            
            'field_watcher' => $this->field_watcher,
        
            'hintContent' => $this->hintContent,
            'balloonContent' => $this->balloonContent,
        ]);
	}
}