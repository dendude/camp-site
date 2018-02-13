<?php
namespace app\modules\manage\controllers;

use app\models\EmailTemplates;
use app\models\forms\UploadForm;
use app\models\search\EmailTemplatesSearch;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

class MailTemplatesController extends Controller
{
    const LIST_NAME = 'Письма';
    const LIST_TEMPLATES = 'Шаблоны писем';
    
    public function beforeAction($action) {
        if ($action->id == 'upload') {
            Yii::$app->request->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }
    
    protected function notFound($message = 'Шаблон не найден') {
        Yii::$app->session->setFlash('error', $message);
        $this->redirect(['list'])->send();
        Yii::$app->end();
    }
    
    public function actionList() {
        $searchModel = new EmailTemplatesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        
        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
    public function actionAdd() {
        $model = new EmailTemplates();
        if (Yii::$app->request->post('EmailTemplates')) {
            
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Шаблон успешно добавлен');
                return $this->redirect(['list']);
            }
        }
        
        return $this->render('add', [
            'model' => $model
        ]);
    }
    
    public function actionEdit($id) {
        $model = EmailTemplates::findOne($id);
        if (!$model) $this->notFound();
        
        if (Yii::$app->request->post('EmailTemplates')) {
            $model->load(Yii::$app->request->post());
            
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Шаблон успешно изменен');
                return $this->redirect(['list']);
            }
        }

        return $this->render('add', [
            'model' => $model
        ]);
    }
    
    public function actionUpload() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $upload_form = new UploadForm();
        $upload_form->imageFile = UploadedFile::getInstances($upload_form, 'imageFile');
        
        if ($upload_form->upload(UploadForm::TYPE_EMAILS)) {
            return ['filelink' => $upload_form->getImagePath(true)];
        } else {
            return $upload_form->getErrors();
        }
    }
}
