<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "delivery_info".
 *
 * @property int $id 唯一标示
 * @property int $resume_id 简历id
 * @property int $recruit_id 简历id
 * @property string $create_time 创建时间
 * @property int $status 状态，1:带查看，2:拒绝，3:约面试，4:入职通知，5:禁止投递
 * @property string $user_id 用户id
 */
class DeliveryInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'delivery_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['resume_id', 'recruit_id', 'status'], 'integer'],
            [['create_time'], 'safe'],
            [['user_id'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'resume_id' => 'Resume ID',
            'recruit_id' => 'Recruit ID',
            'create_time' => 'Create Time',
            'status' => 'Status',
            'user_id' => 'User ID',
        ];
    }
}
