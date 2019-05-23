<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "learning_experience".
 *
 * @property int $id
 * @property string $start_date 入学时间
 * @property string $end_date 毕业时间
 * @property string $school_name 学校名称
 * @property string $major 专业
 * @property int $res_id 关联简历标示
 */
class LearningExperience extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'learning_experience';
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
            [['school_name'], 'string', 'max' => 50],
            [['major'], 'string', 'max' => 20],
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
            'school_name' => 'School Name',
            'major' => 'Major',
            'res_id' => 'Res ID',
        ];
    }
}
