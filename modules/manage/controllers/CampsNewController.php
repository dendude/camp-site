<?php
namespace app\modules\manage\controllers;

use app\models\Camps;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;

class CampsNewController extends Controller
{
    const LIST_NAME = 'Новые заявки';
    
    protected function notFound($message = 'Лагерь не найдена') {
        Yii::$app->session->setFlash('error', $message);
        $this->redirect(['list'])->send();
        Yii::$app->end();
    }
    
    public function actionList()
    {
        $query = Camps::find()->waiting();
    
        $countQuery = clone $query;
        
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize' => 4
        ]);
        $models = $query->offset($pages->offset)->limit($pages->limit)->all();
    
        return $this->render('list', [
            'models' => $models,
            'pages' => $pages,
        ]);
    }
}
