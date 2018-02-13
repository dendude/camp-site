<?php
namespace app\modules\manage\controllers;

use app\helpers\Statuses;
use app\models\Reviews;
use app\models\search\ReviewsSearch;
use Yii;
use yii\web\Controller;

class ReviewsController extends Controller
{
    const LIST_NAME = 'Отзывы';

    protected function notFound($message = 'Отзыв не найден') {
        Yii::$app->session->setFlash('error', $message);
        $this->redirect(['list'])->send();
        Yii::$app->end();
    }

    public function actionList()
    {
        $searchModel = new ReviewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionAdd() {
        $model = new Reviews();

        if (Yii::$app->request->post('Reviews')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Отзыв успешно добавлен');
                return $this->redirect(['list']);
            }
        }

        return $this->render('add', [
            'model' => $model
        ]);
    }

    public function actionEdit($id) {

        $model = Reviews::findOne($id);
        if (!$model) $this->notFound();

        if (Yii::$app->request->post('Reviews')) {

            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Отзыв успешно сохранен');
                if (Yii::$app->request->post('ref-page')) {
                    return $this->redirect(Yii::$app->request->post('ref-page'));
                } else {
                    return $this->redirect(['list']);
                }
            }
        }

        return $this->render('add', [
            'model' => $model
        ]);
    }

    public function actionDelete($id, $confirm = false) {

        $model = Reviews::findOne($id);
        if (!$model) $this->notFound();

        if ($confirm) {
            $model->updateAttributes(['status' => Statuses::STATUS_REMOVED]);
            Yii::$app->session->setFlash('success', 'Отзыв успешно удален');
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
