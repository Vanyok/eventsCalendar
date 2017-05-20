<?php
/**
 * Created by PhpStorm.
 * User: vanyok
 * Date: 18.05.17
 * Time: 22:24
 */

namespace app\models;


use yii\db\ActiveRecord;

/**
 * Invite model
 *
 *
 * @property string $mail
 * @property string $token
 */
class Invite extends ActiveRecord
{

    public static function tableName()
    {
        return 'invite';
    }

    /**
     * check token and allow register
     * @param $token
     * @return bool
     */
    public static function canRegister($token)
    {
        if (isset($token)) {
            $invite = Invite::find()->where(['token' => $token])->one();
            if (isset($invite)) {
                $invite->delete();
                return true;
            }
        }
        return false;
    }

    /**
     * generate token for user
     * @return string
     */
    public function generateToken()
    {
        $this->token = md5($this->mail . date('Y-m-d'));
        return $this->token;
    }
}