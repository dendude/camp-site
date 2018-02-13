<?php
namespace app\modules\manage\controllers;

use app\helpers\Statuses;
use app\models\ReviewsItems;
use app\models\search\ReviewsItemsSearch;
use Yii;
use yii\web\Controller;

class ReviewsItemsController extends Controller
{
    const LIST_NAME = 'Критерии отзывов';

    protected function notFound($message = 'Критерий отзывов не найден') {
        Yii::$app->session->setFlash('error', $message);
        return $this->redirect(['list']);
    }

    public function actionList()
    {
        $searchModel = new ReviewsItemsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionAdd() {
        $model = new ReviewsItems();

        if (Yii::$app->request->post('ReviewsItems')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Критерий отзывов успешно добавлен');
                return $this->redirect(['list']);
            }
        }

        return $this->render('add', [
            'model' => $model
        ]);
    }

    public function actionEdit($id) {

        $model = ReviewsItems::findOne($id);
        if (!$model) return $this->notFound();

        if (Yii::$app->request->post('ReviewsItems')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Критерий отзывов успешно сохранен');
                return $this->redirect(['list']);
            }
        }

        return $this->render('add', [
            'model' => $model
        ]);
    }

    public function actionDelete($id, $confirm = false) {

        $model = ReviewsItems::findOne($id);
        if (!$model) return $this->notFound();

        if ($confirm) {
            $model->updateAttributes(['status' => Statuses::STATUS_REMOVED]);
            Yii::$app->session->setFlash('success', 'Критерий отзывов успешно удален');
            return $this->redirect(['list']);
        }

        return $this->render('delete', [
            'model' => $model
        ]);
    }

    public function actionTrash($id) {
        $this->actionDelete($id, true);
    }
}
