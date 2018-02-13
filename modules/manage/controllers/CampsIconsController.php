<?php
namespace app\modules\manage\controllers;

use app\models\Camps;
use app\models\forms\UploadForm;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use app\models\search\IconsSearch;
use app\helpers\Statuses;
use app\models\Icons;
use yii\web\UploadedFile;

class CampsIconsController extends Controller
{
    const LIST_NAME = 'Иконки для лагерей';

    protected function notFound($message = 'Иконка не найдена') {
        Yii::$app->session->setFlash('error', $message);
        return $this->redirect(['list']);
    }

    public function actionList()
    {
        $searchModel = new IconsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionAdd() {
        $model = new Icons();

        if (Yii::$app->request->post('Icons')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Иконка успешно добавлена');
                return $this->redirect(['list']);
            }
        }

        return $this->render('add', [
            'model' => $model
        ]);
    }

    public function actionEdit($id) {

        $model = Icons::findOne($id);
        if (!$model) $this->notFound();
        
        if (Yii::$app->request->post('Icons')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Иконка успешно сохранена');
                return $this->redirect(['list']);
            }
        }

        return $this->render('add', [
            'model' => $model
        ]);
    }

    public function actionDelete($id, $confirm = false) {

        $model = Icons::findOne($id);
        if (!$model) $this->notFound();

        if ($confirm) {
            $model->updateAttributes(['status' => Statuses::STATUS_REMOVED]);
            Yii::$app->session->setFlash('success', 'Иконка успешно удалена');
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
        $model = Icons::findOne($id);
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
