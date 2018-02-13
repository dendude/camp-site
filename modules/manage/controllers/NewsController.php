<?php
namespace app\modules\manage\controllers;

use app\helpers\Statuses;
use app\models\forms\UploadFileForm;
use app\models\forms\UploadForm;
use app\models\News;
use app\models\Pages;
use app\models\search\NewsSearch;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\UploadedFile;

class NewsController extends Controller
{
    const LIST_NAME = 'Новости';
    
    public function beforeAction($action)
    {
        if ($action->id == 'upload') {
            Yii::$app->request->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }
    
    
    protected function notFound($message = 'Новость не найдена') {
        Yii::$app->session->setFlash('error', $message);
        $this->redirect(['list'])->send();
        Yii::$app->end();
    }
    
    public function actionList()
    {
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        
        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
    public function actionAdd() {
        $model = new News();
        
        if (Yii::$app->request->post('News')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Новость успешно добавлена');
                return $this->redirect(['list']);
            }
        }
        
        return $this->render('add', [
            'model' => $model
        ]);
    }
    
    public function actionEdit($id) {
        
        $model = News::findOne($id);
        if (!$model) $this->notFound();
        
        if (Yii::$app->request->post('News')) {
            
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
    
    public function actionShow($id) {
        $model = News::findOne($id);
        if (!$model) $this->notFound();
        
        return $this->redirect(['/site/new', 'alias' => $model->alias]);
    }
    
    public function actionDelete($id, $confirm = false) {
        
        $model = News::findOne($id);
        if (!$model) $this->notFound();
        
        if ($confirm) {
            $model->updateAttributes(['status' => Statuses::STATUS_REMOVED]);
            Yii::$app->session->setFlash('success', 'Страница успешно удалена');
            return $this->redirect(['list']);
        }
        
        return $this->render('delete', [
            'model' => $model
        ]);
    }
    
    public function actionTrash($id) {
        $this->actionDelete($id, true);
    }
    
    public function actionUpload() {
        $upload_form = new UploadForm();
        $upload_form->imageFile = UploadedFile::getInstances($upload_form, 'imageFile');
        
        if ($upload_form->upload(UploadForm::TYPE_NEWS)) {
            echo Json::encode([
                'file_name' => $upload_form->getImageName()
            ]);
        } else {
            echo Json::encode($upload_form->getErrors());
        }
    }
    
    public function actionUploadImage() {
        $upload_form = new UploadForm();
        $upload_form->imageFile = UploadedFile::getInstances($upload_form, 'imageFile');
        if ($upload_form->upload(UploadForm::TYPE_NEWS)) {
            echo Json::encode(['link' => $upload_form->getImagePath()]);
        } else {
            echo Json::encode($upload_form->getErrors());
        }
    }
    
    public function actionSaveTempContent() {
        return false;
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
