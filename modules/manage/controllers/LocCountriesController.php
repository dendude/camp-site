<?php
namespace app\modules\manage\controllers;

use app\helpers\Statuses;
use app\models\forms\UploadFileForm;
use app\models\forms\UploadForm;
use app\models\LocCountries;
use app\models\search\LocCountriesSearch;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\UploadedFile;

class LocCountriesController extends Controller
{
    const LIST_NAME = 'Страны';
        
    protected function notFound($message = 'Страна не найдена') {
        Yii::$app->session->setFlash('error', $message);
        $this->redirect(['list'])->send();
        Yii::$app->end();
    }
    
    public function actionList()
    {
        $searchModel = new LocCountriesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        
        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
    public function actionAdd() {
        $model = new LocCountries();
        
        if (Yii::$app->request->post('LocCountries')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Страна успешно добавлена');
                return $this->redirect(['list']);
            }
        }
        
        return $this->render('add', [
            'model' => $model
        ]);
    }
    
    public function actionEdit($id) {
        
        $model = LocCountries::findOne($id);
        if (!$model) $this->notFound();
        
        if (Yii::$app->request->post('LocCountries')) {
            
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
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
        
        $model = LocCountries::findOne($id);
        if (!$model) $this->notFound();
        
        if ($confirm) {
            $model->updateAttributes(['status' => Statuses::STATUS_REMOVED]);
            Yii::$app->session->setFlash('success', 'Страна успешно удалена');
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
