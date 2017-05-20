<?php

namespace app\controllers;

use app\models\Event;
use app\models\Invite;
use app\models\InviteForm;
use dektrium\user\models\RegistrationForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'invite'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['invite'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->getIsAdmin();
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function init()
    {
        parent::init();// TODO: Change the autogenerated stub
        /* refresh event statuses for get it actual every time */
        Event::refreshEventsStatus();
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Sends invitation mails
     * @return \yii\web\Response
     */
    public function actionInvite()
    {
        $response = new \yii\web\Response();
        $response->setStatusCode(200);
        if (Yii::$app->request->isPost) {
            $form = new InviteForm();
            $form->attributes = $_POST['InviteForm'];

            if ($form->invite()) {
                $response->content = json_encode(array(
                    'mess' => InviteForm::INVITE_SUCCESS,
                    'status' => 'success'
                ));
                return $response;
            }
        }
        $response->content = json_encode(array(
            'mess' => Yii::t('app', 'Error in data'),
            'status' => InviteForm::INVITE_ERROR
        ));
        return $response;
    }

    /**
     * Displays events calendar page.
     *
     * @return string
     */
    public function actionCalendar()
    {
        $token = Yii::$app->request->get('token');
        $register = Invite::canRegister($token);
        return $this->render('calendar',
            ['currDate' => date('Y-m-d'),
                'register' => $register]);
    }


    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Get calendar part via ajax
     * @return \yii\web\Response
     */
    public function actionMonth()
    {
        if (Yii::$app->request->isPost) {

            $currDate = $_POST['day'];
            $html = $this->renderAjax('_month', ['currDate' => $currDate]);
            $response = new \yii\web\Response();
            $response->setStatusCode(200);
            $response->content = json_encode(array(
                'html' => $html,
                'status' => 'success'
            ));

            return $response;
        }
        die;
    }

    /**
     * Register new users
     * @return string
     */
    public function actionRegister()
    {

        /** @var RegistrationForm $model */
        $model = \Yii::createObject(RegistrationForm::className());
        $register = true;
        $message = null;
        if ($model->load(\Yii::$app->request->post()) && $model->register()) {

            $message = \Yii::t('user', 'Your account has been created');
            $register = false;

        }

        return $this->render('calendar',
            ['currDate' => date('Y-m-d'),
                'register' => $register,
                'message' => $message]);
    }

}
