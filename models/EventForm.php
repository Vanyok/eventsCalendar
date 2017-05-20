<?php
/**
 * Created by PhpStorm.
 * User: vanyok
 * Date: 18.05.17
 * Time: 22:15
 */

namespace app\models;


use Yii;
use yii\base\Model;

class EventForm extends Model
{
    public $date_start;
    public $date_end;
    public $hrs_start = '9:00am';
    public $hrs_end = '6:00pm';
    public $name;
    public $description;
    public $user_id;
    public $color;
    public $status = Event::STATUS_NEW;
    public $post;
    public $responseStatus;

    const EVENT_SUCCESS_MESSAGE = 'Event is created!';
    const EVENT_ERROR_MESSAGE = 'Error in data';

    /**
     *
     * @property string $date_start
     * @property string $date_end
     * @property string $description
     * @property integer $user_id
     * @property integer $status
     * @property string $color
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['name', 'date_start', 'date_end'], 'required'],
            // rememberMe must be a boolean value
            // password is validated by validatePassword()
            [['name', 'date_start', 'date_end', 'hrs_start', 'hrs_end', 'description', 'color'], 'safe'],
        ];
    }

    /**
     * creates new event
     * @return bool
     */
    public function createEvent()
    {

        if (strtotime($this->date_start . ' ' . $this->hrs_start) > strtotime($this->date_end . ' ' . $this->hrs_end)) return false;
        $event = new Event();
        $event->attributes = $this->attributes;
        $event->user_id = Yii::$app->user->id;
        $event->date_start = date('Y-m-d H:i:s', strtotime($this->date_start . ' ' . $this->hrs_start));
        $event->date_end = date('Y-m-d H:i:s', strtotime($this->date_end . ' ' . $this->hrs_end));
        $event->time_start = date('Hi', strtotime($this->hrs_start));
        $event->time_end = date('Hi', strtotime($this->hrs_end));
        $event->status = Event::STATUS_NEW;
        return $event->save(false);
    }

    /**
     * update events dates
     * @return string
     */
    public function updateEvent()
    {
        if (isset($this->post['eventId'])) $event = Event::getEventForUpdateRemove($this->post['eventId']);
        $this->responseStatus = 'error';
        if (isset($this->post['dayDate']) && isset($event) && isset($this->post['type'])) {
            switch ($this->post['type']) {
                case 'start':
                    $startTime = date('H:i:s', strtotime($event->date_start));
                    $event->date_end = $event->getShiftedEndDate($event->date_start, $this->post['dayDate'], $event->date_end);
                    $event->date_start = date('Y-m-d', strtotime($this->post['dayDate'])) . ' ' . $startTime;
                    $event->save(false);
                    $this->responseStatus = 'success';
                    break;
                case 'end':

                    $endTime = date('H:i:s', strtotime($event->date_end));
                    $event->date_end = date('Y-m-d', strtotime($this->post['dayDate'])) . ' ' . $endTime;
                    if (strtotime($event->date_end) <= strtotime($event->date_start)) break;
                    $event->save(false);
                    $this->responseStatus = 'success';
                    break;
            }
        }
        return $this->responseStatus;
    }
}