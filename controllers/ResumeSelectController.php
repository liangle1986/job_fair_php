<?php

namespace app\controllers;
use app\models\Resume;
use app\models\LearningExperience;
use app\models\WorkExperience;
use app\components\Output;
use app\components\Request;
use yii\data\Pagination;
use app\models\DeliveryInfo;
use Yii2;

/**
 * This is the model class for table "resume".
 * 查询简历信�?
 * @property int $id
 * @property string $postal_code 邮政编码
 * @property string $url_id 目前做为唯一图片链接地址
 * @property string $username 用户�?
 * @property int $sex 1:男，2:�?
 * @property string $identitycard 学历0:初中或以下，1:高中�?:中专�?:大专、高职，4：本科，5：研究生�?:博士�?：博士后�?:其他
 * @property string $education 政治面貌
 * @property int $age 年龄
 * @property string $province 省份
 * @property string $city �?
 * @property string $county �?�?
 * @property string $place 户籍�?
 * @property string $domicile 现住�?
 * @property string $phone 手机
 * @property int $status 1:公开�?:关闭�?：企业可看，4:投递可�?
 * @property string $remark 简历详�?
 * @property int $userId 简历关联用户id
 * @property string $resume_code 简历编�?
 * @property string $record_date 登记日期
 * @property string $nation 民族
 * @property int $marital_status 婚姻状态，0:未婚�?:已婚�?:已婚已育
 * @property string $home_phone 家庭电话
 * @property int $personnel_type 0:失业�?:征地�?:协保�?:下岗�?:退休，5:应期毕业生，6：外来媳妇，7:退伍军�?
 * @property string $technical_title 职称
 * @property int $working_life 工作年限
 * @property string $strong_point 特长
 * @property string $Job_intention 求职意向
 * @property string $expected_income 期望收入
 */
class ResumeSelectController extends \yii\web\Controller
{
    /**
     * 默认根据用户查询简历，有可能存在多�?
     */
    public function init(){
    	$this->enableCsrfValidation = false;
    }
    public function actionIndex()
    {
        $userId = Request::Get("UserId");
        //查询主信�?
        $query = Resume::find();

        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $query->count(),
        ]);
        if($userId)
        $query->where(['userId'=> $userId]);

        $countries = $query
            // ->orderBy('id')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
            
     
        $result = array();
    	if($countries){
            foreach($countries as $keys){
                    // $map['id']=$keys->id;
                    // $map['postal_code']=$keys->postal_code;
                    // $map['url_id']=$keys->url_id;
                    // $map['username']=$keys->username;
                    // $map['sex']=$keys->sex;
                    // $map['identitycard']=$keys->identitycard;
                    // $map['education']=$keys->education;
                    // $map['age']=$keys->age;
                    // $map['province']=$keys->province;

                    // $map['city']=$keys->city;
                    // $map['county']=$keys->county;
                    // $map['place']=$keys->place;
                    // $map['domicile']=$keys->domicile;
                    // $map['phone']=$keys->phone;
                    // $map['status']=$keys->status;
                    // $map['remark']=$keys->remark;
                    // $map['userId']=$keys->userId;
                    // $map['resume_code']=$keys->resume_code;
                    // $map['record_date']=$keys->record_date;
                    // $map['nation']=$keys->nation;
                    // $map['marital_status']=$keys->marital_status;
                    // $map['home_phone']=$keys->home_phone;
                    // $map['personnel_type']=$keys->personnel_type;
                    // $map['technical_title']=$keys->technical_title;
                    // $map['working_life']=$keys->working_life;
                    // $map['strong_point']=$keys->strong_point;
                    // $map['Job_intention']=$keys->Job_intention;
                    // $map['expected_income']=$keys->expected_income;
                    $map['resume'] =$keys;
                    // $map["LearningExperience"] = $this->selectLearning($keys["id"]);
                    // $map["WorkExperience"] = $this->selectWorkExperience($keys["id"]);
                    $map['resume']['workarray_data']=json_decode($map['resume']['workarray_data']);
                    $map['resume']['leparray_data']=json_decode($map['resume']['leparray_data']);
                    array_push($result,$map);
    
            }
        }
        $recruit = [
            'countries' => $result,
            'pagination' => $pagination,
        ];

     
        return Output::Code(200, $recruit, "success");

    }

    /**
     * 获取学校信息
     */
    private function selectLearning($resId){
        $result = array();
        //查询学习经历
        $learn = LearningExperience::find()->where(["res_id"=>$resId])->asArray()->all();
        // if($learn){
            // foreach($learn as $keys){
            //     $mapl['id']=$keys->id;
            //     $mapl['start_date']=$keys->start_date;
            //     $mapl['end_date']=$keys->end_date;
            //     $mapl['school_name']=$keys->school_name;
            //     $mapl['major']=$keys->major;
            //     $mapl['res_id']=$keys->res_id;
            //     array_push($result,$mapl);
            // }
            
        // } 
        return $learn;
    }

    /**
     * 获取工作经历信息
     */
    private function selectWorkExperience($resId){
        $result = array();
        //查询学习经历
        $work = WorkExperience:: find()->where(["res_id"=>$resId])->asArray()->all();
        // if($work){
        //     foreach($work as $keys){
        //         $map['id']=$keys->id;
        //         $map['start_date']=$keys->start_date;
        //         $map['end_date']=$keys->end_date;
        //         $map['corporate_name']=$keys->corporate_name;
        //         $map['work_name']=$keys->work_name;
        //         $map['res_id']=$keys->res_id;
        //         array_push($result,$map);
        //     }
        // }
        return $work;
    }


    /**
     * 根据用户获取是否有简历信�?
     */
    public function actionCount(){
        $userId = $_POST["UserId"];
        $resume = Resume::find()->where(['userId'=> $userId])->all();
        if(sizeof($resume)){
            return Output::Code(200, 1, "success");
        } else {
            return Output::Code(200, 2, "success");
        }
    }
    
    /**
     * 根据企业ID查询企业投递的简历信息列�?
     */
    public function actionSelectPage(){
        $value = Request::Get("recruitId");
     
        $query=Resume::find()->getDeliveryInfo($value);

        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $query->count(),
        ]);
    
        $countries = $query
            ->orderBy('id')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
           
        $recruit = [
            'countries' => $countries,
            'pagination' => $pagination,
        ];
  
    	if($recruit){
            return Output::Code(200, $recruit, "success");
    	}else{
            return Output::code(200, '', "success");
    	}
    }

  

}
