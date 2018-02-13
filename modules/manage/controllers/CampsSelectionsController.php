<?php
namespace app\modules\manage\controllers;

use app\models\Camps;
use app\models\forms\UploadForm;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use app\models\search\SelectionsSearch;
use app\helpers\Statuses;
use app\models\Selections;
use yii\web\UploadedFile;

class CampsSelectionsController extends Controller
{
    const LIST_NAME = 'Подборки лагерей';

    protected function notFound($message = 'Подборка не найдена') {
        Yii::$app->session->setFlash('error', $message);
        return $this->redirect(['list']);
    }

    public function actionList()
    {
        $searchModel = new SelectionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionAdd() {
        $model = new Selections();

        if (Yii::$app->request->post('Selections')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Подборка успешно добавлена');
                return $this->redirect(['list']);
            }
        }

        return $this->render('add', [
            'model' => $model
        ]);
    }

    public function actionEdit($id) {

        $model = Selections::findOne($id);
        if (!$model) $this->notFound();
        
        if ($model->status == Statuses::STATUS_NEW) {
            $model->updateAttributes(['status' => Statuses::STATUS_SHOWED]);
        }

        if (Yii::$app->request->post('Selections')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Подборка успешно сохранена');
                return $this->redirect(['list']);
            }
        }

        return $this->render('add', [
            'model' => $model
        ]);
    }

    public function actionDelete($id, $confirm = false) {

        $model = Selections::findOne($id);
        if (!$model) $this->notFound();

        if ($confirm) {
            $model->updateAttributes(['status' => Statuses::STATUS_REMOVED]);
            Yii::$app->session->setFlash('success', 'Подборка успешно удалена');
            return $this->redirect(['list']);
        }

        return $this->render('delete', [
            'model' => $model
        ]);
    }

    public function actionTrash($id) {
        $this->actionDelete($id, true);
    }
    
    public function actionShow($id)
    {
        $model = Selections::findOne($id);
        if (!$model) $this->notFound();
        
        return $this->redirect(['/site/camps', 'type' => Camps::TYPE_TYPE, 'alias' => $model->type->alias]);
    }
    
    public function actionUpload() {
        $upload_form = new UploadForm();
        $upload_form->imageFile = UploadedFile::getInstances($upload_form, 'imageFile');
        
        if ($upload_form->upload(UploadForm::TYPE_PAGES)) {
            echo Json::encode([
                'file_name' => $upload_form->getImageName()
            ]);
        } else {
            echo Json::encode($upload_form->getErrors());
        }
    }
}
