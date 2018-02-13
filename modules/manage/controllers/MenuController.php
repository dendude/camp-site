<?php
namespace app\modules\manage\controllers;

use app\helpers\RedirectHelper;
use app\helpers\Statuses;
use app\models\Menu;
use yii\web\Controller;
use Yii;

class MenuController extends Controller
{
    const LIST_NAME = 'Меню сайта';
    
    protected function notFound($msg = 'Пункт меню не найден') {
        Yii::$app->session->setFlash('error', $msg);
        $this->redirect(['list'])->send();
        Yii::$app->end();
    }
    
    public function actionList()
    {
        return $this->render('list');
    }
    
    public function actionAdd($id = 0)
    {
        $model = new Menu();
        
        if (Yii::$app->request->post('Menu')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Запись успешно добавлена');
                return $this->redirect(['list']);
            }
        } else {
            $model->parent_id = $id;
        }
        
        return $this->render(
            'add', [
                'model' => $model
            ]
        );
    }
    
    public function actionEdit($id)
    {
        $model = Menu::findOne($id);
        
        if (!$model) $this->notFound();
        
        if (Yii::$app->request->post('Menu')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Запись успешно изменена');
                return $this->redirect(['list']);
            }
        }
        
        return $this->render(
            'add', [
                'model' => $model
            ]
        );
    }
    
    public function actionShow($id) {
        Menu::updateAll(['status' => Statuses::STATUS_ACTIVE], ['id' => $id]);
        
        if (Yii::$app->request->isAjax) {
            echo $this->renderPartial('list');
        } else {
            $this->redirect(['list']);
        }
    }
    
    public function actionHide($id) {
        Menu::updateAll(['status' => Statuses::STATUS_DISABLED], ['id' => $id]);
        
        if (Yii::$app->request->isAjax) {
            echo $this->renderPartial('list');
        } else {
            return $this->redirect(['list']);
        }
    }
    
    public function actionUp($id) {
        $current = Menu::findOne($id);
        if ($current) {
            $prev = Menu::find()->where('parent_id = :pid AND ordering < :ord', [':pid' => $current->parent_id, ':ord' => $current->ordering])->orderBy('ordering DESC')->one();
            if ($prev) {
                $prev_ordering = $prev->ordering;
                $prev->updateAttributes(['ordering' => $current->ordering]);
                $current->updateAttributes(['ordering' => $prev_ordering]);
            }
        }
        
        if (Yii::$app->request->isAjax) {
            echo $this->renderPartial('list');
        } else {
            return $this->redirect(['list']);
        }
    }
    
    public function actionDown($id) {
        $current = Menu::findOne($id);
        if ($current) {
            $prev = Menu::find()->where('parent_id = :pid AND ordering > :ord', [':pid' => $current->parent_id, ':ord' => $current->ordering])->orderBy('ordering ASC')->one();
            if ($prev) {
                $prev_ordering = $prev->ordering;
                $prev->updateAttributes(['ordering' => $current->ordering]);
                $current->updateAttributes(['ordering' => $prev_ordering]);
            }
        }
        
        if (Yii::$app->request->isAjax) {
            echo $this->renderPartial('list');
        } else {
            return $this->redirect(['list']);
        }
    }
    
    public function actionDelete($id, $is_trash = false)
    {
        $model = Menu::findOne($id);
        if (!$model) $this->notFound();
        
        if ($model->parent_id == 0) $this->notFound('Нельзя удалить корневое меню');
        if ($model->childs) $this->notFound('Существуют вложенные пункты меню <strong>' . $model->menu_name . '</strong> . Удалите сначала их, а потом этот пункт меню');
        
        if ($is_trash) {
            $model->delete();
            Yii::$app->session->setFlash('success', 'Запись успешно удалена');
        } else {
            return $this->render(
                'delete', [
                    'model' => $model
                ]
            );
        }
    }
    
    public function actionTrash($id)
    {
        $this->actionDelete($id, true);
        return $this->redirect(['list']);
    }
}
