<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $openid
 * @property string $session_key
 * @property int $type
 * @property int $sign
 * @property string $username
 * @property int $sex 0:男1:女
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'sign', 'sex'], 'integer'],
            [['openid', 'session_key', 'username'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'openid' => 'Openid',
            'session_key' => 'Session Key',
            'type' => 'Type',
            'sign' => 'Sign',
            'username' => 'Username',
            'sex' => 'Sex',
        ];
    }
}
