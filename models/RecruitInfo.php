<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "recruitInfo".
 *
 * @property int $id
 * @property string $jobName 岗位信息
 * @property int $mansize 招聘人数
 * @property string $age 年龄要求
 * @property string $record 学历0:初中或以下，1:高中，2:中专，3:大专、高职，4：本科，5：研究生，6:博士，7：博士后，8:其他
 * @property string $pay 月薪范围
 * @property string $workingplace 工作地点
 * @property int $company_id 单位标示
 * @property string $work_content 工作内容
 * @property string $work_demand 岗位要求
 * @property string $company_user_name 单位联系人
 * @property string $home_phone 联系手机
 * @property int $scene_join_number 现场参加人数
 * @property int $job_id 岗位信息标示
 * @property int $minpay 薪资范围，小
 * @property int $maxpay 薪资范围，大
 */
class RecruitInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'recruitInfo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mansize', 'company_id', 'scene_join_number', 'job_id', 'minpay', 'maxpay'], 'integer'],
            [['jobName'], 'string', 'max' => 100],
            [['age', 'pay', 'company_user_name'], 'string', 'max' => 50],
            [['record', 'workingplace'], 'string', 'max' => 200],
            [['work_content', 'work_demand'], 'string', 'max' => 1000],
            [['home_phone'], 'string', 'max' => 13],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jobName' => 'Job Name',
            'mansize' => 'Mansize',
            'age' => 'Age',
            'record' => 'Record',
            'pay' => 'Pay',
            'workingplace' => 'Workingplace',
            'company_id' => 'Company ID',
            'work_content' => 'Work Content',
            'work_demand' => 'Work Demand',
            'company_user_name' => 'Company User Name',
            'home_phone' => 'Home Phone',
            'scene_join_number' => 'Scene Join Number',
            'job_id' => 'Job ID',
            'minpay' => 'Minpay',
            'maxpay' => 'Maxpay',
        ];
    }
}
