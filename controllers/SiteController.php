<?php

namespace app\controllers;

use app\components\CampValidate;
use app\components\SendOrderNotifications;
use app\components\SendReviewNotifications;
use app\components\SmtpEmail;
use app\helpers\Normalize;
use app\helpers\Statuses;
use app\models\BasePeriods;
use app\models\BasePlacements;
use app\models\Bonuses;
use app\models\Camps;
use app\models\CampsAbout;
use app\models\CampsClient;
use app\models\CampsContacts;
use app\models\CampsContract;
use app\models\CampsMedia;
use app\models\CampsPlacement;
use app\models\Changes;
use app\models\ComfortTypes;
use app\models\News;
use app\models\queries\CampsQuery;
use app\models\Reviews;
use app\models\Selections;
use app\models\Settings;
use Yii;
use app\helpers\RedirectHelper;
use app\models\BaseItems;
use app\models\forms\LoginForm;
use app\models\forms\SearchForm;
use app\models\LocCities;
use app\models\LocCountries;
use app\models\LocRegions;
use app\models\Orders;
use app\models\Pages;
use app\models\TagsTypes;
use app\models\Users;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use app\models\ContactForm;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\User;

class SiteController extends Controller
{
    const CAMP_DATA_NAME = 'camp-data';
    
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (Yii::$app->request->isAjax) {
            // для долгого заполнения лагеря
            Yii::$app->request->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }


    protected function notFound($message = 'Страница не найдена') {
        throw new NotFoundHttpException($message, 404);
    }
    
    protected function campRegister() {
    
        $is_edit = false;
        $page = Pages::findOne(Pages::PAGE_CAMP_REGISTER_ID);
        
        $session = Yii::$app->session;
        if (!$session->offsetExists(self::CAMP_DATA_NAME)) {
            // для сохранения данных пошагово
            $session[self::CAMP_DATA_NAME] = [];
        }
        
        if (Yii::$app->session->hasFlash('camp-success') && !Yii::$app->request->isAjax) {
            // лагерь успешно сохранен - выводит другой шаблон
            return $this->render('camp-register-success', [
                'page' => $page,
                'message' => Yii::$app->session->getFlash('camp-success')
            ]);
        }
        
        // редактирование лагеря
        $camp_id = Yii::$app->request->get('camp_id');
        if ($camp_id) {
            // удаляем сессию во избежание перезаписи редактируемых данных
            $session->offsetUnset(self::CAMP_DATA_NAME);
            /** @var $model Camps */
            $model = Camps::find()->byPartner(Yii::$app->user->id)->andWhere(['id' => $camp_id])->using()->one();
            if (!$model) return $this->redirect(['/camp-register']);
        }
        
        if (empty($model)) {
            /** новый лагерь */
            $model = new Camps();
    
            $base_placement = new BasePlacements();
            $base_period = new BasePeriods();
    
            $base_item = new BaseItems();
            $base_item->setScenario(BaseItems::SCENARIO_PARTNER);
    
            $camp_about = new CampsAbout();
    
            $camp_placement = new CampsPlacement();
    
            $camp_media = new CampsMedia();
    
            $camp_client = new CampsClient();
    
            $camp_contacts = new CampsContacts();
    
            $camp_contract = new CampsContract();
            $camp_contract->setScenario(CampsContract::SCENARIO_PARTNER);
            
            $template_id = Yii::$app->request->get('template');
            if ($template_id) {
                /** @var $camp_template Camps */
                $camp_template = Camps::find()->byPartner(Yii::$app->user->id)->byId($template_id)->using()->one();
                if ($camp_template) {
                    // убираем кеш
                    $session->offsetUnset(self::CAMP_DATA_NAME);
                    
                    // устанавливаем аттирбуты лагеря-шаблона
                    $model->setAttributes($camp_template->attributes);
    
                    $camp_about->setAttributes($camp_template->about->attributes);
                    $camp_about->afterFind();
                    
                    $camp_placement->setAttributes($camp_template->placement->attributes);
                    $camp_placement->afterFind();
                    
                    $camp_media->setAttributes($camp_template->media->attributes);
                    $camp_media->afterFind();
                    
                    $camp_client->setAttributes($camp_template->client->attributes);
                    $camp_client->afterFind();
                    
                    $camp_contacts->setAttributes($camp_template->contacts->attributes);
                    $camp_contacts->afterFind();
                    
                    $camp_contract->setAttributes($camp_template->contract->attributes);
                    $camp_contract->afterFind();
                }
            }
        } else {
            /** редактируем лагерь */
            $is_edit = true;
    
            $base_placement = new BasePlacements();
            $base_placements = BasePlacements::find()->byCamp($model->id)->using()->ordering()->all();
    
            $base_period = new BasePeriods();
            $base_periods = BasePeriods::find()->byCamp($model->id)->using()->ordering()->all();
    
            $base_item = new BaseItems();
            $base_item->setScenario(BaseItems::SCENARIO_PARTNER);
            $base_items = BaseItems::find()->byCamp($model->id)->using()->ordering()->all();
    
            $camp_about = $model->about;
    
            $camp_placement = $model->placement;
    
            $camp_media = $model->media;
    
            $camp_client = $model->client;
    
            $camp_contacts = $model->contacts;
    
            $camp_contract = $model->contract;
            $camp_contract->setScenario(CampsContract::SCENARIO_PARTNER);
        }
        
        if (Yii::$app->request->isAjax) {
            
            $camp_data = Yii::$app->request->post();
            $step = Yii::$app->request->post('step', 7);
    
            $model->load($camp_data);
            $camp_about->load($camp_data);
            $camp_placement->load($camp_data);
            $camp_media->load($camp_data);
            $camp_client->load($camp_data);
            $camp_contacts->load($camp_data);
            $camp_contract->load($camp_data);
            
            // сохраняем данные в сессию на случай если человек
            // обновил страницу и данные вводить по новой грустно
            $session_datа = $session[self::CAMP_DATA_NAME];
            $session_datа = array_merge($session_datа, $camp_data);
            $session[self::CAMP_DATA_NAME] = $session_datа;
            
            $result = ['step' => $step];
            $errors = [];

            switch ($step) {
                case 1:
                    CampValidate::stepSimple($camp_about, $errors);
                    break;

                case 2:
                    CampValidate::stepSimple($camp_placement, $errors);
                    if (!$camp_placement->is_without_places) {
                        $places_models = CampValidate::stepPlacements($errors, $model->id);
                    }
                    break;

                case 3:
                    CampValidate::stepSimple($camp_media, $errors);
                    break;

                case 4:
                    CampValidate::stepSimple($camp_client, $errors);
                    break;

                case 5:
                    CampValidate::stepSimple($camp_contacts, $errors);
                    break;

                case 6:
                    CampValidate::stepSimple($camp_contract, $errors);
                    if ($camp_contract->contract_period_type == CampsContract::PERIOD_ITEMS) {
                        // мульти-валидация периодов сезонности
                        $periods_models = CampValidate::stepPeriods($errors, $model->id);
                    }
                    break;

                default:
                    /** step1 */
                    CampValidate::stepSimple($camp_about, $errors);
                    if ($errors) {$result['step'] = 1; break;}

                    /** step2 */
                    CampValidate::stepSimple($camp_placement, $errors);
                    if (!$camp_placement->is_without_places) {
                        // мульти-валидация типов размещения
                        $places_models = CampValidate::stepPlacements($errors, $model->id);
                    }
                    if ($errors) {$result['step'] = 2; break;}

                    /** step3 */
                    CampValidate::stepSimple($camp_media, $errors);
                    if ($errors) {$result['step'] = 3; break;}

                    /** step4 */
                    CampValidate::stepSimple($camp_client, $errors);
                    if ($errors) {$result['step'] = 4; break;}

                    /** step5 */
                    CampValidate::stepSimple($camp_contacts, $errors);
                    if ($errors) {$result['step'] = 5; break;}

                    /** step6 */
                    CampValidate::stepSimple($camp_contract, $errors);
                    if ($camp_contract->contract_period_type == CampsContract::PERIOD_ITEMS) {
                        // мульти-валидация периодов сезонности
                        $periods_models = CampValidate::stepPeriods($errors, $model->id);
                    }
                    if ($errors) {$result['step'] = 6; break;}

                    /** step7 мульти-валидация смен */
                    $items_models = CampValidate::stepItems($errors, $model->id, BaseItems::SCENARIO_PARTNER);
                    if ($errors) {$result['step'] = 7; break;}
            }

            if (count($errors)) {
                // есть ошибки валидации
                $result['errors'] = $errors;
            } elseif ($step == 7) {
                // последний шаг без ошибок
                if ($model->save()) {
                    // чистим от кеша
                    $session->offsetUnset(self::CAMP_DATA_NAME);
                    
                    if ($is_edit) {
                        $changes = new Changes();
                        $changes->camp_id = $model->id;
                        $changes->partner_id = $model->partner_id;
                        
                        $changes->old_attributes['about'] = CampsAbout::findOne($camp_about->id)->attributes;
                        $changes->old_attributes['placement'] = CampsPlacement::findOne($camp_placement->id)->attributes;
                        $changes->old_attributes['media'] = CampsMedia::findOne($camp_media->id)->attributes;
                        $changes->old_attributes['client'] = CampsClient::findOne($camp_client->id)->attributes;
                        $changes->old_attributes['contacts'] = CampsContacts::findOne($camp_contacts->id)->attributes;
                        $changes->old_attributes['contract'] = CampsContract::findOne($camp_contract->id)->attributes;
                    }
                    
                    /** сохраняем зависимые модели */
                    $camp_about->camp_id = $model->id;
                    $camp_about->save();
    
                    $camp_placement->camp_id = $model->id;
                    $camp_placement->save();
    
                    $camp_media->camp_id = $model->id;
                    $camp_media->save();
        
                    $camp_client->camp_id = $model->id;
                    $camp_client->save();
        
                    $camp_contacts->camp_id = $model->id;
                    $camp_contacts->save();
        
                    $camp_contract->camp_id = $model->id;
                    $camp_contract->save();

                    if ($is_edit) {
                        // фиксируем изменения
                        if (isset($changes)) {
                            $changes->new_attributes['about'] = $model->about->attributes;
                            $changes->new_attributes['placement'] = $model->placement->attributes;
                            $changes->new_attributes['media'] = $model->media->attributes;
                            $changes->new_attributes['client'] = $model->client->attributes;
                            $changes->new_attributes['contacts'] = $model->contacts->attributes;
                            $changes->new_attributes['contract'] = $model->contract->attributes;
                        }
                    } else {
                        // сохраняем алиас для нового лагеря
                        $model->updateAttributes([
                            'status' => Statuses::STATUS_DISABLED,
                            'alias' => Normalize::alias($camp_about->name_short),
                        ]);
                        
                    }

                    /**
                     * сохраняем варианты размещений
                     * @var $places_models BasePlacements[]
                     */
                    $ids = [0];
                    if (!empty($places_models)) {
                        foreach ($places_models AS $m) {
                            
                            if ($is_edit && isset($changes)) {
                                // фиксируем изменения
                                $rand = $m->isNewRecord ? Yii::$app->security->generateRandomString(10) : $m->id;
                                
                                $changes->old_attributes["base_placement_{$rand}"] = $m->isNewRecord ? [] : BasePlacements::findOne($m->id)->attributes;
                                $changes->new_attributes["base_placement_{$rand}"] = $m->attributes;
                            }
                            
                            $m->camp_id = $model->id;
                            $m->save();
                            // массив для учета при удалении
                            $ids[] = $m->id;
                        }
                    }
    
                    if ($is_edit && isset($changes)) {
                        /** @var $removed BasePlacements[] */
                        $removed = BasePlacements::find()->byCamp($model->id)->using()
                                 ->andWhere(['not in', 'id', $ids])->all();
                        
                        if ($removed) {
                            foreach ($removed AS $rm) {
                                // фиксируем изменения
                                $changes->old_attributes["base_placement_{$rm->id}"] = BasePlacements::findOne($rm->id)->attributes;
                                $changes->new_attributes["base_placement_{$rm->id}"] = [];
                                // помечаем удаление
                                $rm->updateAttributes(['status' => Statuses::STATUS_REMOVED]);
                            }
                        }
                    }
    
                    /**
                     * сохраняем сезонность
                     * @var $periods_models BasePeriods[]
                     */
                    $ids = [0];
                    if (!empty($periods_models)) {
                        foreach ($periods_models AS $m) {
                            
                            if ($is_edit && isset($changes)) {
                                // фиксируем изменения
                                $rand = $m->isNewRecord ? Yii::$app->security->generateRandomString(10) : $m->id;
        
                                $changes->old_attributes["base_periods_{$rand}"] = $m->isNewRecord ? [] : BasePeriods::findOne($m->id)->attributes;
                                $changes->new_attributes["base_periods_{$rand}"] = $m->attributes;
                            }
                            
                            $m->partner_id = $model->partner_id;
                            $m->camp_id = $model->id;
                            $m->save();
                            // массив для учета при удалении
                            $ids[] = $m->id;
                        }
                    }
    
                    if ($is_edit && isset($changes)) {
                        /** @var $removed BasePeriods[] */
                        $removed = BasePeriods::find()->byCamp($model->id)->using()
                            ->andWhere(['not in', 'id', $ids])->all();
        
                        if ($removed) {
                            foreach ($removed AS $rm) {
                                // фиксируем изменения
                                $changes->old_attributes["base_periods_{$rm->id}"] = BasePeriods::findOne($rm->id)->attributes;
                                $changes->new_attributes["base_periods_{$rm->id}"] = [];
                                // помечаем удаление
                                $rm->updateAttributes(['status' => Statuses::STATUS_REMOVED]);
                            }
                        }
                    }
    
                    /**
                     * сохраняем смены
                     * @var $items_models BaseItems[]
                     */
                    $ids = [0];
                    if (!empty($items_models)) {
                        foreach ($items_models AS $m) {
                            if ($is_edit && isset($changes)) {
                                // фиксируем изменения
                                $rand = $m->isNewRecord ? Yii::$app->security->generateRandomString(10) : $m->id;
        
                                $changes->old_attributes["base_items_{$rand}"] = $m->isNewRecord ? [] : BaseItems::findOne($m->id)->attributes;
                                $changes->new_attributes["base_items_{$rand}"] = $m->attributes;
                            }
                            
                            $m->partner_id = $model->partner_id;
                            $m->camp_id = $model->id;
                            $m->save();
                            // массив для учета при удалении
                            $ids[] = $m->id;
                        }
                    }
    
                    if ($is_edit && isset($changes)) {
                        /** @var $removed BaseItems[] */
                        $removed = BaseItems::find()->byCamp($model->id)->using()
                            ->andWhere(['not in', 'id', $ids])->all();
        
                        if ($removed) {
                            foreach ($removed AS $rm) {
                                // фиксируем изменения
                                $changes->old_attributes["base_items_{$rm->id}"] = BaseItems::findOne($rm->id)->attributes;
                                $changes->new_attributes["base_items_{$rm->id}"] = [];
                                // помечаем удаление
                                $rm->updateAttributes(['status' => Statuses::STATUS_REMOVED]);
                            }
                        }
                    }
    
                    if ($is_edit) {
                        if (isset($changes) && $model->status == Statuses::STATUS_ACTIVE || Users::isAdmin()) {
                            // только для активного лагеря сохраняем изменения
                            $changes->save();
                        }
                        
                        $user = Users::getProfile();
                        // редирект с сообщением
                        $result['redirect'] = Url::to(['/partner/camps/list']);
                        Yii::$app->session->setFlash('success', 'Лагерь успешно сохранен');
                    } elseif (Yii::$app->user->isGuest) {
                        // создаем учетку партнера
                        $password = preg_replace('/[^a-z0-9]/i', '', Yii::$app->security->generateRandomString(10));
        
                        $user = new Users();
                        $user->first_name = $camp_contacts->boss_fio;
                        $user->email = $camp_contacts->boss_email;
                        $user->phone = $camp_contacts->boss_phone;
                        $user->role = Users::ROLE_PARTNER;
                        $user->pass_origin = $password;
        
                        $check = Users::find()->where(['email' => $user->email])->one();
        
                        if ($check || $user->save()) {
                            if ($check) {
                                $password = 'Ваш пароль, указанный при регистрации';
                                $model->updateAttributes(['partner_id' => $check->id]);
                            } else {
                                $model->updateAttributes(['partner_id' => $user->id]);
                            }
            
                            $smtp = new SmtpEmail();
                            $smtp->sendEmailByType(SmtpEmail::TYPE_CAMP_REGISTERED, $camp_contacts->boss_email, $camp_contacts->boss_fio, [
                                '{email}'    => $camp_contacts->boss_email,
                                '{password}' => $password
                            ]);
                            $smtp->sendEmailByType(SmtpEmail::TYPE_CAMP_REGISTERED, $camp_contacts->worker_email, $camp_contacts->worker_fio, [
                                '{email}'    => $camp_contacts->boss_email,
                                '{password}' => $password
                            ]);
                        }

                        // редирект с сообщением
                        $result['redirect'] = Url::to(['/camp-register']);
                        Yii::$app->session->setFlash('camp-success', 'Лагерь успешно отправлен на модерацию');

                    } else {
                        $user = Users::getProfile();
        
                        if (!Users::isPartner()) {
                            // обновляем роль пользователя на партнера
                            $user->updateAttributes(['role' => Users::ROLE_PARTNER]);
                        }
        
                        $smtp = new SmtpEmail();
                        $smtp->sendEmailByType(SmtpEmail::TYPE_CAMP_REGISTERED, $user->email, $user->first_name, [
                            '{email}'    => $user->email,
                            '{password}' => 'Ваш пароль, указанный при регистрации'
                        ]);

                        // редирект с сообщением
                        $result['redirect'] = Url::to(['/partner/camps/list']);
                        Yii::$app->session->setFlash('success', 'Лагерь успешно отправлен на модерацию');
                    }

                    if (!$is_edit) {
                        $camps_count = Camps::find()->byPartner($user->id)->count();
                        if ($camps_count == 1) {
                            // сохраняем данные договора и контактов
                            // для автозаполнения в следующем лагере
                            $user->contacts_boss_fio = $camp_contacts->boss_fio;
                            $user->contacts_boss_email = $camp_contacts->boss_email;
                            $user->contacts_boss_phone = $camp_contacts->boss_phone;
                            $user->contacts_worker_fio = $camp_contacts->worker_fio;
                            $user->contacts_worker_email = $camp_contacts->worker_email;
                            $user->contacts_worker_phone = $camp_contacts->worker_phone;
                            $user->contacts_office_address = $camp_contacts->office_address;
                            $user->contacts_office_phone = $camp_contacts->office_phone;
                            $user->contacts_office_route = $camp_contacts->office_route;
                            
                            $user->contract_ogrn_serial = $camp_contract->contract_ogrn_serial;
                            $user->contract_ogrn_number = $camp_contract->contract_ogrn_number;
                            $user->contract_ogrn_date = $camp_contract->contract_ogrn_date;
                            $user->contract_inn = $camp_contract->contract_inn;
                            $user->update();
                        }
                        
                        // отправка уведомления о новом лагере
                        $settings = Settings::lastSettings();
                        $emails = Normalize::emailsStrToArr($settings->emails_new_camp);
                        $smtp = new SmtpEmail();
                        foreach ($emails AS $email_notify) {
                            $smtp->sendEmailByType(SmtpEmail::TYPE_NEW_CAMP_NOTIFY, $email_notify, 'Администратор', [
                                '{login-url}' => Url::to(['/auth/login'], true),
                                '{camp-url}' => $model->getCampUrl(true),
                                '{camp-name}' => $camp_about->name_short,
                                '{boss-data}' => implode('<br/>', [
                                    $camp_contacts->boss_fio,
                                    $camp_contacts->boss_email,
                                    $camp_contacts->boss_phone,
                                ]),
                                '{worker-data}' => implode('<br/>', [
                                    $camp_contacts->worker_fio,
                                    $camp_contacts->worker_email,
                                    $camp_contacts->worker_phone,
                                ]),
                            ]);
                        }
                    }
                } else {
                    Yii::warning($model->getErrors(), 'CAMP');
                    $result['errors'] = $model->getErrors();
                }
            }

            Yii::$app->response->format = Response::FORMAT_JSON;
            return $result;
            
        } elseif (!$is_edit && $session->offsetExists(self::CAMP_DATA_NAME)) {
            // подставляем сессионные данные
            // на случай обновления страницы чтобы не вводить заново
            $camp_about->load($session[self::CAMP_DATA_NAME]);
            $camp_placement->load($session[self::CAMP_DATA_NAME]);
            $camp_media->load($session[self::CAMP_DATA_NAME]);
            $camp_client->load($session[self::CAMP_DATA_NAME]);
            $camp_contacts->load($session[self::CAMP_DATA_NAME]);
            $camp_contract->load($session[self::CAMP_DATA_NAME]);
            
            if (Yii::$app->user->id) {
                $user = Users::getProfile();
                // автозаполнение данных из профиля
                $camp_contacts->boss_fio = $user->contacts_boss_fio;
                $camp_contacts->boss_phone = $user->contacts_boss_phone;
                $camp_contacts->boss_email = $user->contacts_boss_email;
                $camp_contacts->worker_fio = $user->contacts_worker_fio;
                $camp_contacts->worker_phone = $user->contacts_worker_phone;
                $camp_contacts->worker_email = $user->contacts_worker_email;
                $camp_contacts->office_address = $user->contacts_office_address;
                $camp_contacts->office_phone = $user->contacts_office_phone;
                $camp_contacts->office_route = $user->contacts_office_route;
                
                $camp_contract->contract_ogrn_serial = $user->contract_ogrn_serial;
                $camp_contract->contract_ogrn_number = $user->contract_ogrn_number;
                $camp_contract->contract_ogrn_date_f = $user->contract_ogrn_date_f;
                $camp_contract->contract_inn = $user->contract_inn;
            }
        }
        
        // выбор шаблона лагеря
        $camp_templates = Camps::find()->joinWith('about')->byPartner(Yii::$app->user->id)->using()->ordering()->all();
        $templates = $camp_templates ? ArrayHelper::map($camp_templates, 'id', 'about.name_short') : null;
        
        return $this->render('camp-register', [
            'page' => $page,

            'model' => $model,
            
            'templates' => $templates,

            'base_item' => $base_item,
            'base_items' => isset($base_items) ? $base_items : null,

            'base_period' => $base_period,
            'base_periods' => isset($base_periods) ? $base_periods : null,

            'base_placement' => $base_placement,
            'base_placements' => isset($base_placements) ? $base_placements : null,

            'camp_contract'  => $camp_contract,
            'camp_contacts'  => $camp_contacts,
            'camp_media'     => $camp_media,
            'camp_placement' => $camp_placement,
            'camp_about'     => $camp_about,
            'camp_client'    => $camp_client,
        ]);
    }

    protected function forParents() {
        $model = Pages::findOne(Pages::PAGE_PARENTS_ID);
        if (!$model) $this->notFound();
        
        $search = new SearchForm();
    
        return $this->render('for-parents', [
            'model' => $model,
            'search' => $search
        ]);
    }
    
    protected function bonuses() {
        $model = Pages::findOne(Pages::PAGE_BONUSES_ID);
        if (!$model) $this->notFound();
        
        $bonuses = Bonuses::find()->active()->ordering()->all();
        
        return $this->render('bonuses', [
            'model' => $model,
            'bonuses' => $bonuses,
        ]);
    }
    
    public function actionNew($alias) {
        $model = News::find()->byAlias($alias)->one();
        if (!$model) $this->notFound();
    
        return $this->render('new', [
            'model' => $model,
        ]);
    }
    
    protected function news() {
        $model = Pages::findOne(Pages::PAGE_NEWS_ID);
        if (!$model) $this->notFound();
    
        $query = News::find()->active();
        $countQuery = clone $query;
    
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize' => 10
        ]);
        $news = $query->ordering()->offset($pages->offset)->limit($pages->limit)->all();
        
        return $this->render('news', [
            'model' => $model,

            'news' => $news,
            'pages' => $pages,
        ]);
    }
    
    protected function showReviews() {
        $model = Pages::findOne(Pages::PAGE_REVIEWS_ID);
        if (!$model) $this->notFound();
    
        $query = Reviews::find()->active();
        $countQuery = clone $query;
        
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize' => 10
        ]);
        $models = $query->offset($pages->offset)->limit($pages->limit)->all();
        
        return $this->render('reviews', [
            'model' => $model,
            
            'models' => $models,
            'pages' => $pages,
        ]);
    }
    
    protected function campGroups() {
        $model = Pages::findOne(Pages::PAGE_GROUPS_ID);
        if (!$model) $this->notFound();

        /** @var $query CampsQuery */
        $query = Camps::find()->byType(TagsTypes::GROUPS_ID)->active();
        $countQuery = clone $query;
        
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize' => 10
        ]);
        $models = $query->offset($pages->offset)->limit($pages->limit)->all();
                
        return $this->render('groups', [
            'model' => $model,
            
            'models' => $models,
            'pages' => $pages,
        ]);
    }
    
    protected function campOrder() {
        $model = Pages::findOne(Pages::PAGE_ORDER);
        if (!$model) $this->notFound();
    
        $camp = Camps::findOne(Yii::$app->request->get('id'));
        if (!$camp || $camp->status != Statuses::STATUS_ACTIVE) $this->notFound('Лагерь не найден');
    
        $order = new Orders();
        
        if (Yii::$app->request->post('Orders')) {
            $order->load(Yii::$app->request->post());
            if ($order->save()) {
                $notifications = new SendOrderNotifications($order);
                $notifications->send();

                Yii::$app->session->setFlash('success', 'Ваша заявка успешно создана! Вы можете оплатить вашу бронь прямо на сайте!');
                Yii::$app->session->setFlash('order_id', $order->id);
    
                return $this->redirect(['/order', 'id' => $camp->id]);
            }
        }
        
        return $this->render('order', [
            'model' => $model,
            'order' => $order,
            'camp' => $camp,
        ]);
    }
    
    protected function campReview() {
        $model = Pages::findOne(Pages::PAGE_REVIEW_ADD);
        if (!$model) $this->notFound();
        
        $camp = Camps::findOne(Yii::$app->request->get('id'));
        if (!$camp) $this->notFound('Лагерь не найден');
        
        $review = new Reviews();
        $review->setScenario(Reviews::SCENARIO_SITE);
        
        if (Yii::$app->request->post('Reviews')) {
            $review->load(Yii::$app->request->post());
            
            if ($review->validate()) {
                $review->save(false);
                
                $notifications = new SendReviewNotifications($review);
                $notifications->send();
                
                Yii::$app->session->setFlash('success', 'Ваш отзыв успешно отправлен и будет опубликован сразу после прохождения модерации!');
                
                return $this->redirect(Pages::getUrlById(Pages::PAGE_REVIEW_ADD, ['id' => $camp->id]));
            }
        }
        
        return $this->render('review-add', [
            'model' => $model,
            'review' => $review,
            'camp' => $camp,
        ]);
    }
    
    protected function catalog() {
        $model = Pages::findOne(Pages::PAGE_CATALOG);
        if (!$model) $this->notFound();
                
        return $this->render('catalog', [
            'model' => $model,
        ]);
    }
    
    protected function selections() {
        $model = Pages::findOne(Pages::PAGE_SELECTIONS);
        if (!$model) $this->notFound();
        
        return $this->render('selections', [
            'model' => $model,
            'selections' => Selections::find()->active()->ordering()->all()
        ]);
    }
    
    public function actionCampMap() {
        $id = Yii::$app->request->get('id');
        $camp = Camps::findOne($id);
        
        if (!$camp) return $this->notFound('Лагерь не найден');
        
        return $this->renderPartial('camp-map', ['model' => $camp]);
    }
    
    public function actionCampPoints() {
        $id = Yii::$app->request->get('id');
        $camp = Camps::findOne($id);
        
        if (!$camp) return $this->notFound('Лагерь не найден');
        
        return $this->renderPartial('camp-points', ['model' => $camp]);
    }

    /**
     * список лагерей и фильтрация
     *
     * @param null $type
     * @param null $alias
     * @return string
     */
    public function actionCamps($type = null, $alias = null) {
        /** @var $query CampsQuery */
        $query = Camps::find()->active()->distinct();
    
        $search = new SearchForm();
    
        $msg = 'К сожалению, мы не нашли ни одного лагеря по Вашему запросу.' . PHP_EOL .
               'Возможно, Вы ошиблись в названии или лагерь был переименован.' . PHP_EOL .
               'Уточните информацию по телефону 8 (800) 222-74-66.';

        switch ($type) {
            case Camps::TYPE_COUNTRY :
                /** @var $model LocCountries */
                $model = LocCountries::find()->where(['alias' => $alias])->one();
                if (!$model) $this->notFound($msg);
                $query->byCountry($model->id);
                $search->country_id = $model->id;
                break;
            
            case Camps::TYPE_REGION :
                /** @var $model LocRegions */
                $model = LocRegions::find()->where(['alias' => $alias])->one();
                if (!$model) $this->notFound($msg);
                $query->byRegion($model->id);
                $search->region_id = $model->id;
                break;
            
            case Camps::TYPE_CITY :
                /** @var $model LocCities */
                $model = LocCities::find()->where(['alias' => $alias])->one();
                if (!$model) $this->notFound($msg);
                $query->byCity($model->id);
                break;
            
            case Camps::TYPE_TRANSFER :
                /** @var $model LocCities */
                $model = LocCities::find()->where(['alias' => $alias])->one();
                if (!$model) $this->notFound($msg);
                $query->transferFrom($model->id);
                break;
            
            case Camps::TYPE_TYPE :
                /** @var $model TagsTypes */
                $model = TagsTypes::find()->where(['alias' => $alias])->one();
                if (!$model) $this->notFound($msg);
                $query->byType($model->id);
                $search->type = $model->id;
                break;
    
            case Camps::TYPE_SERVICE :
                /** @var $model TagsTypes */
                $model = ComfortTypes::find()->where(['alias' => $alias])->one();
                if (!$model) $this->notFound($msg);
                $query->byService($model->id);
                break;
            
            case Camps::TYPE_YEARS :
                @list($age_from, $age_to) = explode('-', $alias);
                $query->byYears($age_from, $age_to);
                break;
    
            case Camps::TYPE_COMPENSATION :
                $query->byGosCompensation();
                break;
/*
            case Camps::TYPE_GROUPS :
                $query->byGroups();
                break;*/

            default:
                $search_params = ['SearchForm' => Yii::$app->request->get()];
                $search->load($search_params);
                
                $alias = Yii::$app->request->get('alias');
                if ($alias) {
                    // для поиска по шаблону /camps/country--region--type
                    $aliases = explode('--', $alias);

                    if (count($aliases) == 1) {
                        // передается страна, регион или тип
                        $cur_alias = array_shift($aliases);
                        $alias = $cur_alias;
    
                        $model = LocCountries::find()->where(['alias' => $cur_alias])->one();
                        if ($model) {$query->byCountry($model->id); $type = Camps::TYPE_COUNTRY; $search->country_id = $model->id; break;}
    
                        $model = LocRegions::find()->where(['alias' => $cur_alias])->one();
                        if ($model) {$query->byRegion($model->id); $type = Camps::TYPE_REGION; $search->region_id = $model->id; break;}
    
                        $model = TagsTypes::find()->where(['alias' => $cur_alias])->one();
                        if ($model) {$query->byType($model->id); $type = Camps::TYPE_TYPE; $search->type = $model->id; break;}

                        // не найдено
                        $query->byCountry(0);
    
                    } elseif (count($aliases) == 2) {
                        // передается страна и тип или страна и регион
                        list($alias_country, $alias_type) = $aliases;
                        $alias = $alias_type;
                            
                        $model = LocCountries::find()->where(['alias' => $alias_country])->one();
                        // указываем 0 для пустого результата поиска
                        $search->country_id = ($model ? $model->id : 0);
                        $query->byCountry($search->country_id);
    
                        $model = TagsTypes::find()->where(['alias' => $alias_type])->one();
                        if ($model->id) {$search->type = $model->id; $type = Camps::TYPE_TYPE; $query->byType($model->id); break;}
                                                    
                        $model = LocRegions::find()->where(['alias' => $alias_type])->one();
                        // указываем 0 для пустого результата поиска
                        $search->region_id = ($model ? $model->id : 0);
                        $query->byRegion($search->region_id);
    
                        $type = Camps::TYPE_REGION;
    
                    } elseif (count($aliases) == 3) {
                        // передается страна и тип
                        list($alias_country, $alias_region, $alias_type) = $aliases;
    
                        $model = LocCountries::find()->where(['alias' => $alias_country])->one();
                        // указываем 0 для пустого результата поиска
                        $search->country_id = ($model ? $model->id : 0);
                        $query->byCountry($search->country_id);
    
                        $model = LocRegions::find()->where(['alias' => $alias_region])->one();
                        // указываем 0 для пустого результата поиска
                        $search->region_id = ($model ? $model->id : 0);
                        $query->byRegion($search->region_id);
    
                        $model = TagsTypes::find()->where(['alias' => $alias_type])->one();
                        // указываем 0 для пустого результата поиска
                        $search->type = ($model ? $model->id : 0);
                        $query->byType($search->type);
    
                        $type = Camps::TYPE_TYPE;
                        $alias = $alias_type;
                    } else {
                        // не найдено
                        $search->country_id = 0;
                        $query->byCountry(0);
                    }
                }
                
                if (!empty($search->city_from)) $query->transferFrom($search->city_from);
                if (!empty($search->date)) {
                    @list($date_from, $date_to) = explode('_', $search->date);
                    $query->byDates($date_from, $date_to);
                }
                if (!empty($search->ages)) {
                    @list($age_from, $age_to) = explode('-', $search->ages);
                    $query->byYears($age_from, $age_to);
                }
                if (!empty($search->compensation)) $query->byGosCompensation();
                if (!empty($search->service)) $query->byService($search->service);
                if (!empty($search->name)) $query->byName($search->name);
        }
        
        if (Yii::$app->request->get('sort') == 'price-asc') {
            $query->orderByPrice(SORT_ASC);
        } elseif (Yii::$app->request->get('sort') == 'price-desc') {
            $query->orderByPrice(SORT_DESC);
        } else {
            $query->ordering();
        }
    
        $countQuery = clone $query;
            
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize' => 10
        ]);
        $models = $query->offset($pages->offset)->limit($pages->limit)->all();
    
        return $this->render('camps', [
            'model' => Pages::findOne(Pages::PAGE_CAMPS_ID),
            'model_type' => (isset($model) ? $model : null),
            
            'type' => $type,
            'alias' => $alias,
            'search' => $search,
        
            'models' => $models,
            'pages' => $pages,
        ]);
    }
    
    public function actionIndex() {
        $model = Pages::findOne(Pages::PAGE_INDEX_ID);
        $search = new SearchForm();
        
        return $this->render('index', [
            'model' => $model,
            'search' => $search
        ]);
    }
    
    public function actionCamp($country, $region, $alias) {
        $data = explode('-', $alias);
        
        $id = array_pop($data);
        $alias = implode('-', $data);
    
        $msg = 'К сожалению, мы не нашли лагерь по Вашему запросу.' . PHP_EOL .
               'Возможно, Вы ошиблись в названии или лагерь был переименован.' . PHP_EOL .
               'Уточните информацию по телефону 8 (800) 222-74-66.';
        
        if (empty($id)) $this->notFound($msg);

        /** @var $model Camps */
        $model = Camps::find()->where(['id' => $id, 'alias' => $alias])->one();
        if (!$model) $this->notFound($msg);
    
        // сценарий для групповых лагерей
        $order = new Orders();
        if ($model->about->isForGroups()) {
            $order->setScenario(Orders::SCENARIO_GROUPS);
        }
        
        return $this->render('camp', [
            'model' => $model,
            'order' => $order
        ]);
    }
    
    /**
     * обработчик страниц
     *
     * @param $alias
     * @return string
     */
    public function actionPage($alias) {
        /** @var $model Pages */
        $model = Pages::find()->byAlias($alias)->one();
        
        if (!$model) $this->notFound('Страница не найдена');
        
        // constant for using in views
        define('CURRENT_PAGE_ID', $model->id);
        
        switch ($model->id) {
            case Pages::PAGE_CAMP_REGISTER_ID :
                return $this->campRegister();
    
            case Pages::PAGE_BONUSES_ID :
                return $this->bonuses();
    
            case Pages::PAGE_NEWS_ID :
                return $this->news();
    
            case Pages::PAGE_REVIEWS_ID :
                return $this->showReviews();
    
            case Pages::PAGE_GROUPS_ID :
                return $this->actionCamps(Camps::TYPE_GROUPS);
    
            case Pages::PAGE_ORDER :
                return $this->campOrder();
    
            case Pages::PAGE_REVIEW_ADD :
                return $this->campReview();
    
            case Pages::PAGE_CATALOG :
                return $this->catalog();
    
            case Pages::PAGE_SELECTIONS :
                return $this->selections();
        }
        
        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('page', [
                'model' => $model
            ]);
        }
        
        return $this->render('page', [
            'model' => $model
        ]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            if (Users::isAdmin()) {
                RedirectHelper::go(['/manage/main/index']);
            } else {
                RedirectHelper::go(['/office/main/index']);
            }
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
}
