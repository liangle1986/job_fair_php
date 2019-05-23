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
use Yii;

class ResumeController extends \yii\web\Controller
{
	public function init(){
    	$this->enableCsrfValidation = false;
    }
    public function actionIndex()
    {
        $userId = Request::Post("UserId");
        //æŸ¥è¯¢ä¸»ä¿¡æ?
        $query = Resume::find();

        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $query->count(),
        ]);
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
                $map['resume'] =$keys;
                $map['resume']['workarray_data']=json_decode($map['resume']['workarray_data']);
                $map['resume']['leparray_data']=json_decode($map['resume']['leparray_data']);
                
                array_push($result,$map);
    
            }
        }
        $recruit = [
            'countries' => $result,
            'pagination' => $pagination,
        ];     
        return Output::Code(200, $recruit, "success".$userId);
    }

    public function actionUpdate(){
    	try{
            $res = array(
                "id" => Request::Post("id")?Request::Post("id"):0,
                "url_id" => Request::Post("url_id"),
                "username" => Request::Post("username"),
                "identitycard" => Request::Post("identitycard"),
                "birthday"=>Request::Post("birthday"),
                "education" => Request::Post("education")?Request::Post("education"):0,
                "sex" => Request::Post("sex"),
                "province" => Request::Post("province"),
                "city" => Request::Post("city"),
                "county" => Request::Post("county"),
                "place" => Request::Post("place")?Request::Post("place"):0,
                "domicile" => Request::Post("domicile"),
                "phone" => Request::Post("phone"),
                "status" => Request::Post("status"),
                "remark" => Request::Post("remark"),
                "userId" => Request::Post("userId"),
                "resume_code" => Request::Post("resume_code"),
                "record_date" => Request::Post("record_date"),
                "nation" => Request::Post("nation"),
                "marital_status" => Request::Post("marital_status")?Request::Post("marital_status"):0,
                "home_phone" => Request::Post("home_phone"),
                "personnel_type" => Request::Post("personnel_type")?Request::Post("personnel_type"):0,
                "Job_intention" => Request::Post("Job_intention"),
                "expected_income" => Request::Post("expected_income"),
                "political_status" => Request::Post("political_status")?Request::Post("political_status"):0,
                "street" => Request::Post("street"),
                "person_height" => Request::Post("person_height")?Request::Post("person_height"):0,
                "workarray_data"=>Yii::$app->request->post("workarray_data"),
                "leparray_data"=>Yii::$app->request->post("leparray_data")
            );
           	if((int)$res["id"]==0){
           		$model = new Resume;
           	}else{
           		$model = Resume::findOne((int)$res["id"]);
           	}


            $model->sex = $res["sex"]."";
            if($res["url_id"])
            $model->url_id = $res["url_id"];
            if($res["username"])
            $model->username = $res["username"];
            if($res["identitycard"])
            $model->identitycard = $res["identitycard"];
            if($res["birthday"])
            $model->birthday = (int)$res["birthday"];
            if($res["education"])
            $model->education = (int)$res["education"];
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
            if($res["status"])
            $model->status = $res["status"];
            if($res["remark"])
            $model->remark = $res["remark"];
            if($res["userId"])
            $model->userId = $res["userId"];
            if($res["resume_code"])
            $model->resume_code = $res["resume_code"];
            if($res["record_date"])
            $model->record_date = $res["record_date"];
            if($res["nation"])
            $model->nation = $res["nation"];
            if($res["marital_status"])
            $model->marital_status = $res["marital_status"];
            if($res["home_phone"])
            $model->home_phone = $res["home_phone"];
            if($res["personnel_type"])
            $model->personnel_type = $res["personnel_type"];
            if($res["Job_intention"])
            $model->Job_intention = $res["Job_intention"];
            if($res["expected_income"])
            $model->expected_income = $res["expected_income"];
            if($res["political_status"])
            $model->political_status = $res["political_status"];
            if($res["street"])
            $model->street = $res["street"];
            if($res["person_height"])
            $model->person_height = $res["person_height"];
            if($res["workarray_data"])
            $model->workarray_data=json_encode($res["workarray_data"]);
            if($res["leparray_data"])
            $model->leparray_data=json_encode($res["leparray_data"]);
            if($model->save()) {
                return Output::Code(200, "", "更新成功".$res["sex"]); 
            } else {
                return Output::Code(500, "", "更新失败".$res["sex"]);  
            }
        }catch(Exception $e){
            return Output::Code(-100, "", "更新失败"); 
        }
    }

}
