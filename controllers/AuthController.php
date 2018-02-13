<?php

namespace app\controllers;

use app\components\SmtpEmail;
use app\helpers\RedirectHelper;
use app\helpers\Statuses;
use app\models\forms\LoginForm;
use app\models\forms\RegisterForm;
use app\models\forms\RestoreForm;
use app\models\Users;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;

class AuthController extends Controller
{
    public function beforeAction($action)
    {
        if ($action->id != 'logout' && !Yii::$app->user->isGuest) {
            if (Users::isAdmin()) {
                RedirectHelper::go(['/manage/main/index']);
            } else {
                RedirectHelper::go(['/office/main/index']);
            }
        }
        
        return parent::beforeAction($action);
    }
    
    public function actionLogin() {
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            switch (true) {
                case Users::isAdmin() :
                    return $this->redirect(['/manage/main/index']);
                case Users::isPartner() :
                    return $this->redirect(['/partner/main/index']);
                default:
                    return $this->redirect(['/office/main/index']);
            }
        }
        
        return $this->render('login', [
            'model' => $model,
        ]);
    }
    
    public function actionRegister() {
        $model = new RegisterForm();
        
        if (Yii::$app->request->post('RegisterForm')) {
            $model->load(Yii::$app->request->post());
            
            if ($model->validate() && $model->register()) {
                Yii::$app->session->setFlash('success', 'Вы успешно зарегистрировались на сайте!<br/>Письмо с дальнейшими инструкциями отправлено на указанный Email.');
                return $this->redirect(['register']);
            }
        }
        
        return $this->render('register', [
            'model' => $model
        ]);
    }
    
    public function actionActivate($id = 0) {
        
        $user_code = Yii::$app->request->get('code');
        
        $msg = ['Ошибка активации учётной записи.'];
        $class = 'danger';
        
        if ($id && $user_code) {
            /** @var $user Users */
            $user = Users::find()->where(['id' => $id, 'act_code' => $user_code])->one();
            if ($user) {
                
                $class = 'success';
                
                if ($user->status == Statuses::STATUS_ACTIVE) {
                    // уже активирован или даже подтвержден менеджерами
                    $msg = ['Учетная запись уже активирована!'];
                    $msg[] = 'Вы можете вайти в свой личный кабинет.';
                    
                } else {
                    $msg = ['Учетная запись успешно активирована!'];
                    $msg[] = 'Теперь вы можете войти в личный кабинет.';
                                        
                    // выходим
                    if ( !Yii::$app->user->isGuest) Yii::$app->user->logout(false);
                    
                    $password = $user->pass;
                    
                    // активируем
                    $user->updateAttributes([
                        'status' => Statuses::STATUS_ACTIVE,
                        'pass' => Yii::$app->security->generatePasswordHash($password)
                    ]);
    
                    $login_url = Url::to(['/auth/login'], true);
                    
                    // уведомление об успешной активации
                    $smtp = new SmtpEmail();
                    $smtp->sendEmailByType(SmtpEmail::TYPE_REG_CONFIRM, $user->email, $user->first_name, [
                        '{login_url}' => Html::a($login_url, $login_url),
                        '{login}' => $user->email,
                        '{password}' => $password
                    ]);
                }
                
            } else {
                $msg[] = 'Неверный код активации';
            }
        } else {
            $msg[] = 'Отсутствуют параметры для активации';
        }
        
        return $this->render('activate',[
            'title' => 'Активация учетной записи',
            'message' => implode('<br />', $msg),
            'class' => $class
        ]);
    }
    
    public function actionRestore() {
        $model = new RestoreForm();
        
        if (Yii::$app->request->post('RestoreForm')) {
            $model->load(Yii::$app->request->post());
            
            if ($model->validate() && $model->restore()) {
                Yii::$app->session->setFlash('success', 'На ваш Email отправлено письмо для подтверждения сброса пароля!<br/>После подтверждения Вы получите новый пароль для входа в личный кабинет.');
                return $this->redirect(['restore']);
            }
        }
        
        return $this->render('restore', [
            'model' => $model
        ]);
    }
    
    public function actionReset($id = 0) {
        $user_code = Yii::$app->request->get('code');
        
        $msg = ['Ошибка восстановления пароля.'];
        $class = 'danger';
        
        if ($id && $user_code) {
            /** @var $user Users */
            $user = Users::findOne($id);
            if ($user) {
                if ($user->act_code === $user_code) {
                    $new_pass = substr(md5(time()), 0, 10);
                    
                    $user->updateAttributes(['act_code' => '',
                                             'pass' => Yii::$app->security->generatePasswordHash($new_pass)]);
                    
                    $login_url = Url::to(['/auth/login'], true);
                    
                    $smtp = new SmtpEmail();
                    $smtp->sendEmailByType(SmtpEmail::TYPE_RESTORE_CONFIRM, $user->email, $user->first_name, [
                        '{login_url}' => Html::a($login_url, $login_url),
                        '{login}' => $user->email,
                        '{password}' => $new_pass
                    ]);
                    
                    $class = 'success';
                    
                    $msg = [];
                    $msg[] = 'Новый пароль отправлен на ваш Email.';
                    $msg[] = 'Теперь для входа в личный кабинет используйте ваш Email и новый пароль из письма.';
                    
                } else {
                    $msg[] = 'Неверный код для сброса пароля';
                }
            } else {
                $msg[] = 'Пользователь не найден';
            }
        } else {
            $msg[] = 'Не переданы параметры для восстановления пароля';
        }
        
        return $this->render('activate',[
            'title' => 'Создан новый пароль',
            'message' => implode('<br />', $msg),
            'class' => $class
        ]);
    }

    public function actionLogout() {
        if (Yii::$app->user->id) {
            Yii::$app->user->logout(false);
        }
        
        return $this->goHome();
    }
}
