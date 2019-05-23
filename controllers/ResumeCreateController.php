<?php

namespace app\controllers;
use yii;
use app\models\Resume;
use app\models\LearningExperience;
use app\models\WorkExperience;
use app\components\Output;
use app\components\Request;
/**
 * 创建
 *  * @property int $id
 * @property string $postal_code 邮政编码
 * @property string $url_id 目前做为唯一图片链接地址
 * @property string $username 用户名
 * @property int $sex 1:男，2:女
 * @property string $identitycard 学历0:初中或以下，1:高中，2:中专，3:大专、高职，4：本科，5：研究生，6:博士，7：博士后，8:其他
 * @property string $education 政治面貌
 * @property int $age 年龄
 * @property string $province 省份
 * @property string $city 市
 * @property string $county 县/区
 * @property string $place 户籍地
 * @property string $domicile 现住地
 * @property string $phone 手机
 * @property int $status 1:公开，2:关闭，3：企业可看，4:投递可看
 * @property string $remark 简历详情
 * @property int $userId 简历关联用户id
 * @property string $resume_code 简历编码
 * @property string $record_date 登记日期
 * @property string $nation 民族
 * @property int $marital_status 婚姻状态，0:未婚，1:已婚，2:已婚已育
 * @property string $home_phone 家庭电话
 * @property int $personnel_type 0:失业，1:征地，2:协保，3:下岗，4:退休，5:应期毕业生，6：外来媳妇，7:退伍军人
 * @property string $technical_title 职称
 * @property int $working_life 工作年限
 * @property string $strong_point 特长
 * @property string $Job_intention 求职意向
 * @property string $expected_income 期望收入
 * @property string $street 街道
 * @property int $person_height 身高
 * @property int $weight 体重
 */
class ResumeCreateController extends \yii\web\Controller
{
    public function init(){ $this->enableCsrfValidation = false; }
    public function actionIndex()
    {
        try{
             // 'id', 'name', 'url_id', 'username', 'sex', 'identitycard', 'education', 'age', 'province', 'city', 'county', 'place', 'domicile', 'phone', 'status', 'remark', 'userId
            $model = new Resume;
                // "id" => Request::Get("id"],
            $res =[            
                "postal_code" => Request::Post("postal_code"),
                "url_id" => Request::Post("url_id"),
                "username" => Request::Post("username"),
                "identitycard" => Request::Post("identitycard"),
                "education" => Request::Post("education")?Request::Post("education"):0,
                "sex" => Request::Post("sex"),
                "age" => Request::Post("age"),
                "province" => Request::Post("province"),
                "city" => Request::Post("city"),
                "county" => Request::Post("county"),
                "place" => Request::Post("place")?Request::Post("place"):0,
                "domicile" => Request::Post("domicile"),
                "phone" => Request::Post("phone"),
                "remark" => Request::Post("remark"),
                "userId" => Request::Post("userId"),
                "resume_code" => Request::Post("resume_code"),
                "nation" => Request::Post("nation"),
                "marital_status" => Request::Post("marital_status")?Request::Post("marital_status"):0,
                "home_phone" => Request::Post("home_phone"),
                "personnel_type" => Request::Post("personnel_type")?Request::Post("personnel_type"):0,
                "technical_title" => Request::Post("technical_title"),
                "working_life" => Request::Post("working_life"),
                "strong_point" => Request::Post("strong_point"),
                "Job_intention" => Request::Post("Job_intention"),
                "expected_income" => Request::Post("expected_income"),
                "political_status" => Request::Post("political_status")?Request::Post("political_status"):0,
                "street" => Request::Post("street"),
                "person_height" => Request::Post("person_height")?Request::Post("person_height"):0,
                "weight" => Request::Post("weight")?Request::Post("weight"):0,
                "workarray_data"=>Yii::$app->request->post("workarray_data"),
                "leparray_data"=>Yii::$app->request->post("leparray_data")
            ];

          
       
            if($res["postal_code"])
            $model->postal_code = $res["postal_code"];
            if($res["url_id"])
            $model->url_id = $res["url_id"];
            if($res["username"])
            $model->username = $res["username"];
            if($res["sex"])
            $model->sex = (int)$res["sex"];
            if($res["identitycard"])
            $model->identitycard = $res["identitycard"];
            if($res["education"])
            $model->education = (int)$res["education"];
            if($res["age"])
            $model->age = (int)$res["age"];
            if($res["province"])
            $model->province = $res["province"];
            if($res["city"])
            $model->city = $res["city"];
            if($res["county"])
            $model->county = $res["county"];
            if($res["place"])
            $model->place = $res["place"];
            if($res["domicile"])
            $model->domicile = $res["domicile"];
            if($res["phone"])
            $model->phone = $res["phone"];
            $model->status = 0;
            if($res["remark"])
            $model->remark = $res["remark"];
            if($res["userId"])
            $model->userId = $res["userId"];

            if($res["resume_code"])
            $model->resume_code = $res["resume_code"];
         
            $model->record_date = date("Y-m-d");
            if($res["nation"])
            $model->nation = $res["nation"];
            if($res["marital_status"])
            $model->marital_status =$res["marital_status"];
            if($res["home_phone"])
            $model->home_phone = $res["home_phone"];
            if($res["personnel_type"])
            $model->personnel_type = $res["personnel_type"];
            if($res["technical_title"])
            $model->technical_title = $res["technical_title"];
            if($res["working_life"])
            $model->working_life = $res["working_life"];
            if($res["strong_point"])
            $model->strong_point = $res["strong_point"];
            if($res["Job_intention"])
            $model->Job_intention = $res["Job_intention"];
            if($res["expected_income"])
            $model->expected_income = $res["expected_income"];
            if($res["political_status"])
            $model->expected_income = $res["political_status"];
            if($res["street"])
            $model->street = $res["street"];
            if($res["person_height"])
            $model->person_height = $res["person_height"];
            if($res["weight"])
            $model->weight = $res["weight"];
            if($res["workarray_data"])
            $model->workarray_data=json_encode($res["workarray_data"]);
            if($res["leparray_data"])
            $model->leparray_data=json_encode($res["leparray_data"]);
          

            if($model->save()) {
             

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
            // $leparr = array(
            //     "start_date" => Request::Get("start_date"),
            //     "end_date" => Request::Get("end_date"),
            //     "school_name" => Request::Get("school_name"),
            //     "major" => Request::Get("major")
            // );
            // $leArray = Yii::$app->request->post("leparray_data");

            // foreach($leArray as $leparr){
            //     //创建学习经历
            //     $this->saveLearning($leparr, $model->id);
            // }
             
                //创建工作经历
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
                // $work = array(
                //     "start_date" => Request::Get("start_date"),
                //     "end_date" =>Request::Get("end_date"),
                //     "corporate_name" =>Request::Get("corporate_name"),
                //     "work_name" => Request::Get("work_name"),
                // );
                // $workArray = Yii::$app->request->post("workarray_data");

                // foreach($workArray as $work){
                //     //创建学习经历
                //     $this->saveWorkExperience($work, $model->id);
                // }
                 
               
                return Output::Code(200, "新增成功", Request::Post("userId")); 
            } else {
                // return $model->createCommand()->getRawSql();
                return Output::Code(500, "新增失败。",json_encode($res));  
            }
        }catch(Exception $e){
            // var_dump($e);
            return Output::Code(-100, "新增失败", "error"); 
        }
      
    }

    /**
     * 创建学习经历
     */
    private function saveLearning($leparr, $resId){
        $lep = new LearningExperience;
        if($leparr["start_date"])
        $lep->start_date = $leparr["start_date"];
        if($leparr["end_date"])
        $lep->end_date = $leparr["end_date"];
        if($leparr["school_name"])
        $lep->school_name = $leparr["school_name"];
        if($leparr["major"])
        $lep->major = $leparr["major"];
        $lep->res_id = $resId;
        $lep->save();
    }
    /**
     * 创建工作经历
     */
    private function saveWorkExperience($work, $resId){
        $works = new WorkExperience;
        if($work["start_date"])
        $works->start_date = $work["start_date"];
        if($work["end_date"])
        $works->end_date = $work["end_date"];
        if($work["corporate_name"])
        $works->corporate_name = $work["corporate_name"];
        if($work["work_name"])
        $works->work_name = $work["work_name"];
        $works->res_id = $resId;
        $works->save();
    }

}
