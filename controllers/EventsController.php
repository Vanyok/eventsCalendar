<?php
/**
 * Created by PhpStorm.
 * User: vanyok
 * Date: 18.05.17
 * Time: 19:48
 */

namespace app\controllers;

use app\models\Event;
use app\models\EventForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class EventsController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'remove', 'update'],
                'rules' => [
                    [
                        'actions' => ['create', 'remove', 'update'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'create' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Creates new event
     * @return \yii\web\Response
     */
    public function actionCreate()
    {
        if (Yii::$app->request->isPost) {
            $form = new EventForm();
            $form->attributes = $_POST['EventForm'];
            $response = new \yii\web\Response();
            $response->setStatusCode(200);
            if ($form->createEvent()) {
                $response->content = json_encode(array(
                    'mess' => EventForm::EVENT_SUCCESS_MESSAGE,
                    'status' => 'success'
                ));
            } else {
                $response->content = json_encode(array(
                    'mess' => Yii::t('app', 'Error in data'),
                    'status' => EventForm::EVENT_ERROR_MESSAGE
                ));
            };
            return $response;
        }
        die;
    }

    /**
     * validates ajax form
     * @return bool
     */
    public function actionValidate()
    {
        if (Yii::$app->request->isPost) {
            $form = new EventForm();
            $form->attributes = $_POST['EventForm'];
            return $form->validate();
        }
        die;
    }


    /**
     * removes event
     * @return \yii\web\Response
     */
    public function actionRemove()
    {
        if (isset($_POST['eventId'])) $event = Event::getEventForUpdateRemove($_POST['eventId']);
        $status = 'error';
        if (isset($event)) {
            $event->is_removed = 1;
            $event->save(false);
            $status = 'success';
        }
        $response = new \yii\web\Response();
        $response->setStatusCode(200);
        $response->content = json_encode(array(
            'status' => $status
        ));
        return $response;
    }

    /**
     * Updates event (all cases)
     * @return \yii\web\Response
     */
    public function actionUpdate()
    {
        if (Yii::$app->request->isPost) {
            $eventForm = new EventForm();
            $eventForm->post = Yii::$app->request->post();
            $response = new \yii\web\Response();
            $response->setStatusCode(200);
            $response->content = json_encode(array(
                'status' => $eventForm->updateEvent()
            ));
            return $response;
        }
        die;
    }

}