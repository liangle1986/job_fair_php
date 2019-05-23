<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jobInfo".
 *
 * @property int $id
 * @property string $job_name 岗位名称
 * @property string $remarks 岗位描述
 */
class JobInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jobInfo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['job_name'], 'string', 'max' => 50],
            [['remarks'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'job_name' => 'Job Name',
            'remarks' => 'Remarks',
        ];
    }
}
