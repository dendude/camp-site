<?php
namespace app\widgets;

use app\models\Camps;
use yii\base\Widget;

class CampsNew extends Widget {
        
	public function run() {
        $camps = Camps::find()->active()->orderBy(['id' => SORT_DESC])->limit(10)->all();
        if (!$camps) return '';
	    	    
		return $this->render('CampsNew', [
		    'camps' => $camps
        ]);
	}
}