<?php
namespace app\modules\manage\controllers;

use app\helpers\Statuses;
use app\models\EmailMass;
use app\models\forms\UploadFileForm;
use app\models\forms\UploadForm;
use app\models\search\EmailMassSearch;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

class MailSendsController extends Controller
{
    const LIST_NAME = 'Рассылки';
    const TEMP_NAME = 'temp-mass';
        
    protected function notFound($message = 'Рассылка не найдена') {
        Yii::$app->session->setFlash('error', $message);
        $this->redirect(['list'])->send();
        Yii::$app->end();
    }
    
    public function actionList()
    {
        $searchModel = new EmailMassSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        
        return $this->render(
            'list', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ]
        );
    }
    
    public function actionAdd() {
        $model = new EmailMass();
        
        if (Yii::$app->request->post('EmailMass')) {
            $model->load(Yii::$app->request->post());
            if ($model->validate()) {
                $model->save();
                return $this->redirect(['list']);
            }
        }
        
        return $this->render('add', [
            'model' => $model
        ]);
    }
    
    public function actionEdit($id) {
        
        $model = EmailMass::findOne($id);
        if (!$model) $this->notFound();
        
        if (Yii::$app->request->post('EmailMass')) {
            $model->load(Yii::$app->request->post());
            if ($model->validate()) {
                $model->save();
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
    
    public function actionShow($id)
    {
        $model = EmailMass::findOne($id);
        if (!$model) $this->notFound();
        
        if ($model->alias == 'index') {
            return $this->redirect('/');
        } else {
            return $this->redirect(["/$model->alias"]);
        }
    }
    
    public function actionDelete($id, $confirm = false) {
        $model = EmailMass::findOne($id);
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
    
    public function actionSaveTempContent() {
        $id = Yii::$app->request->post('id', '');
        $content = Yii::$app->request->post('content', '');
        
        Yii::$app->session->set(self::TEMP_NAME . $id, $content);
    }
    
    public function actionUploadImage() {
        $upload_form = new UploadForm();
        $upload_form->imageFile = UploadedFile::getInstances($upload_form, 'imageFile');
        if ($upload_form->upload(UploadForm::TYPE_EMAILS)) {
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
    
    public function actionFilesManager() {
        
        $result = [];
        
        $common_path = 'img/pages-editor';
        
        $dir = new \DirectoryIterator(Yii::getAlias("@app/web/{$common_path}"));
        
        while ($dir->valid()) {
            if ($dir->isDir() || $dir->isDot()) {
                $dir->next();
                continue;
            }
            
            $file_name = $dir->getFilename();
    
            $result[] = [
                'url' => "/{$common_path}/$file_name",
                'thumb' => "/{$common_path}/$file_name",
                'tag' => 'Картинки писем',
            ];
    
            $dir->next();
        }
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $result;
    }
    
    public function actionFilesManagerDelete() {
        $src = Yii::$app->request->post('src');
        if ($src) UploadForm::remove($src);
    }
}
