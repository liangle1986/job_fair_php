<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "work_experience".
 *
 * @property int $id
 * @property string $start_date 开始时间
 * @property string $end_date 结束时间
 * @property string $corporate_name 公司名称
 * @property string $work_name 岗位名称
 * @property int $res_id 关联简历标示
 */
class WorkExperience extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'work_experience';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['start_date', 'end_date'], 'safe'],
            [['res_id'], 'required'],
            [['res_id'], 'integer'],
            [['corporate_name'], 'string', 'max' => 50],
            [['work_name'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'corporate_name' => 'Corporate Name',
            'work_name' => 'Work Name',
            'res_id' => 'Res ID',
        ];
    }
}
