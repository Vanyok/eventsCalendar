<?php
/**
 * Created by PhpStorm.
 * User: vanyok
 * Date: 18.05.17
 * Time: 21:46
 */

namespace app\models;


use Yii;
use yii\db\ActiveRecord;

/**
 * Event model
 *
 * @property integer $id
 * @property string $name
 * @property string $date_start
 * @property integer $time_start
 * @property string $date_end
 * @property integer $time_end
 * @property string $description
 * @property integer $user_id
 * @property integer $status
 * @property string $color
 * @property boolean $is_removed
 */
class Event extends ActiveRecord
{
    const STATUS_NEW = 1;
    const STATUS_IN_PROGRESS = 2;
    const STATUS_DONE = 3;

    /**
     * Get related user object
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(\dektrium\user\models\User::className(), ['id' => 'user_id']);
    }

    /* get contrast color for description */
    function getFontColor()
    {
        if (isset($this->color) && $this->color != '') {
            return (hexdec($this->color) > 0xffffff / 2) ? 'black' : 'white';
        } else {
            return 'black';
        }

    }

    public static function tableName()
    {
        return 'event';
    }

    public function safeAttributes()
    {
        return ['name', 'date_start', 'date_end', 'description', 'color'];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('label', 'Тема'),
            'description' => Yii::t('label', 'Дата'),
        ];
    }

    /**
     * Get times in string for views
     * @return string
     */
    public function getStartEndTime()
    {
        return date('H:i', strtotime($this->date_start)) . ' - ' . date('H:i', strtotime($this->date_end));
    }

    /**
     * get status in string for views
     * @return mixed
     */
    public function getStatusMess()
    {
        $messages = [
            Event::STATUS_NEW => '',
            Event::STATUS_IN_PROGRESS => '(in process)',
            Event::STATUS_DONE => '(done)'
        ];
        return $messages[$this->status];
    }

    /**
     * Find events at certain date
     * @param $date
     * @return array|\yii\db\ActiveRecord[]
     */
    static public function getEventsByDate($date)
    {
        $start = date('Y-m-d H:s:i', strtotime($date . ' 00:00:00'));
        $end = date('Y-m-d H:s:i', strtotime($date . ' 00:00:00 +1 day'));
        $events = Event::find()->where('date_start < :end AND date_end > :start AND is_removed != 1', [':start' => $start, ':end' => $end])->orderBy('date_start')->all();
        return $events;
    }

    /**
     * refresh events status in DB (just update queries)
     */
    static public function refreshEventsStatus()
    {
        $date = date('Y-m-d H:s:i');
        Yii::$app->db->createCommand()->update('event', ['status' => Event::STATUS_IN_PROGRESS], 'date_start <= :now AND date_end > :now AND status != :status')->bindParam(':now', $date)->bindValue(':status', Event::STATUS_IN_PROGRESS)->execute();
        Yii::$app->db->createCommand()->update('event', ['status' => Event::STATUS_DONE], 'date_end <= :now  AND status != :status')->bindParam(':now', $date)->bindValue(':status', Event::STATUS_DONE)->execute();
        Yii::$app->db->createCommand()->update('event', ['status' => Event::STATUS_NEW], 'date_start > :now  AND status != :status')->bindParam(':now', $date)->bindValue(':status', Event::STATUS_NEW)->execute();
    }

    /**
     * Get end date related on new start date and previous end date
     * @param $origStartDate
     * @param $newStartDate
     * @param $endDate
     * @return false|string
     */
    public function getShiftedEndDate($origStartDate, $newStartDate, $endDate)
    {
        $origTime = strtotime(date('Y-m-d', strtotime($origStartDate)));
        $newTime = strtotime(date('Y-m-d', strtotime($newStartDate)));
        $dateDiff = floor(($newTime - $origTime) / (60 * 60 * 24));
        if ($dateDiff > 0) {
            return date('Y-m-d H:i:s', strtotime("+" . $dateDiff . " day", strtotime($endDate)));
        } elseif ($dateDiff < 0) {
            return date('Y-m-d H:i:s', strtotime($dateDiff . " day", strtotime($endDate)));
        }
    }

    /**
     * Find and return event if user can modify it
     * @param $id
     * @return array|null|ActiveRecord
     */
    public static function getEventForUpdateRemove($id)
    {

        $event = Event::find()->where(['id' => $id])->one();
        if (isset($event)) {
            if (!Yii::$app->user->isGuest && (Yii::$app->user->identity->getIsAdmin() || Yii::$app->user->id == $event->author->id)) {
                return $event;
            }
        }

        return null;
    }
}