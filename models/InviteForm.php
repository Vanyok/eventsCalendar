<?php

namespace app\models;

use yii\base\Model;
use yii\helpers\Url;
use yii\log\Logger;

/**
 * InviteForm is the model behind the contact form.
 */
class InviteForm extends Model
{

    public $email;
    const INVITE_SUCCESS = 'Invitation sen successfully';
    const INVITE_ERROR = 'Error during send invite';

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['email'], 'required'],
            // email has to be a valid email address
            ['email', 'email']
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function invite()
    {
        $invite = new Invite();
        $invite->mail = $this->email;

        $body = 'You can register at our site. Pls follow the link : <a href="' . Url::to(['site/calendar', 'token' => $invite->generateToken()], true) . '">' . Url::to(['site/calendar', 'token' => $invite->generateToken()], true) . '</a> ';
        if ($this->validate()) {
            $log = new Logger();
            try {
                $message = \Swift_Message::newInstance()
                    ->setSubject('Please, register on beautiful portal of events')
                    ->setFrom(['no-reply@calendar.vanyok.in.ua' => 'Event calendar'])
                    ->setTo($this->email)
                    ->setBody($body, 'text/html')
                    ->addPart($body, 'text/plain');
                $log->addInfo('Sending " mail " to ' . $this->email);

                $transport = \Swift_MailTransport::newInstance();
                $mailer = \Swift_Mailer::newInstance($transport);
                $mailer->send($message);
                $invite->save(false);
                return true;
            } catch (\Exception $e) {
                $log->addError($e->getMessage());
                return false;
            }
        }
        return false;
    }
}
