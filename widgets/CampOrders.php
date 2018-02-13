<?php
namespace app\widgets;

use app\models\Orders;
use yii\base\Widget;

class CampOrders extends Widget {
        
	public function run() {
	    $orders = Orders::find()->orderBy(['id' => SORT_DESC])->using()->limit(30)->all();
	    if (!$orders) return '';
	    
	    $ids = [];
	    foreach ($orders AS $ok => $ov) {
	        if (in_array($ov->camp_id, $ids)) unset($orders[$ok]);
	        $ids[] = $ov->camp_id;
        }
	    	    
		return $this->render('CampOrders', [
		    'orders' => $orders
        ]);
	}
}