<?php
namespace app\widgets;

use yii\base\Widget;
use app\models\Camps;

class CampRecommend extends Widget {
    
    public $limit = 1;
    public $class = 'item-one';
        
	public function run() {
        $camps = Camps::find()->orderFree()->active()->orderBy('RAND()')->limit($this->limit)->all();
	    if (!$camps) return '';

	    if ($this->limit > 1) $this->class = null;
	    	    
		return $this->render('CampRecommend', [
		    'camps' => $camps,
            'class' => $this->class
        ]);
	}
}