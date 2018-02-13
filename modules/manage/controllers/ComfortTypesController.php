<?php
namespace app\modules\manage\controllers;

use app\models\forms\UploadFileForm;
use app\models\forms\UploadForm;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use app\helpers\Statuses;
use app\models\ComfortTypes;
use app\models\search\ComfortTypesSearch;
use yii\web\UploadedFile;

class ComfortTypesController extends Controller
{
    const LIST_NAME = 'Удобства и услуги';

    protected function notFound($message = 'Запись не найдена') {
        Yii::$app->session->setFlash('error', $message);
        return $this->redirect(['list']);
    }

    public function actionList()
    {
        $searchModel = new ComfortTypesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionAdd() {
        $model = new ComfortTypes();

        if (Yii::$app->request->post('ComfortTypes')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Запись успешно добавлена');
                return $this->redirect(['list']);
            }
        }

        return $this->render('add', [
            'model' => $model
        ]);
    }

    public function actionEdit($id) {

        $model = ComfortTypes::findOne($id);
        if (!$model) return $this->notFound();

        if (Yii::$app->request->post('ComfortTypes')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Запись успешно сохранена');
                return $this->redirect(['list']);
            }
        }

        return $this->render('add', [
            'model' => $model
        ]);
    }

    public function actionDelete($id, $confirm = false) {

        $model = ComfortTypes::findOne($id);
        if (!$model) return $this->notFound();

        if ($confirm) {
            $model->updateAttributes(['status' => Statuses::STATUS_REMOVED]);
            Yii::$app->session->setFlash('success', 'Запись успешно удалена');
            return $this->redirect(['list']);
        }

        return $this->render('delete', [
            'model' => $model
        ]);
    }

    public function actionTrash($id) {
        $this->actionDelete($id, true);
    }
    
    public function actionSaveTempContent() {
        return false;
    }
    
    public function actionUploadImage() {
        $upload_form = new UploadForm();
        $upload_form->imageFile = UploadedFile::getInstances($upload_form, 'imageFile');
        if ($upload_form->upload(UploadForm::TYPE_PAGES)) {
            echo Json::encode(['link' => $upload_form->getImagePath()]);
        } else {
            echo Json::encode($upload_form->getErrors());
        }
    }
    
    public function actionUploadFiles() {
        $upload_form = new UploadFileForm();
        $upload_form->docFile = UploadedFile::getInstance($upload_form, 'docFile');
        if ($upload_form->upload()) {
            echo Json::encode(['link' => $upload_form->getDocPath()]);
        } else {
            echo Json::encode($upload_form->getErrors());
        }
    }
}
