<?php

namespace app\widgets;

use Yii;
use app\models\forms\SearchForm;
use yii\base\Widget;

class OfficeCurses extends Widget {
        
	public function run() {
		return $this->render('OfficeCurses');
	}
}