<?php

namespace app\controllers;
use app\models\RecruitInfo;
use app\components\Output;
use app\components\Request;
/**
 * 修改企业招聘信息
 */
class RecruitUpdateController extends \yii\web\Controller
{
    public function init(){
        $this->enableCsrfValidation = false;
    }
    
    public function actionIndex()
    {
        $data = array(
            "id"=> Request::Post("id"),
            "unitName"=> Request::Post("unitName"),
            "jobName"=> Request::Post("jobName"),
            "mansize"=> Request::Post("mansize"),
            "age"=> Request::Post("age"),
            "record"=> Request::Post("record"),
            "pay"=> Request::Post("pay"),
            "jobrequirements"=> Request::Post("jobrequirements"),
            "workingplace"=> Request::Post("workingplace")
        );
        $model;
        if($data["id"]){
            // id', 'unitName', 'jobName', 'mansize', 'age', 'record', 'pay', 'jobrequirements', 'workingplace'
            $model = RecruitInfo::findOne($data["id"]);
        } else {
            $model = new RecruitInfo;
        }
      
        if($data["unitName"])
        $model->unitName = $data["unitName"];
        if($data["jobName"])
        $model->jobName = $data["jobName"];
        if($data["mansize"])
        $model->mansize = $data["mansize"];
        if($data["age"])
        $model->age = $data["age"];
        if($data["record"])
        $model->record = $data["record"];
        if($data["pay"])
        $model->pay = $data["pay"];
        if($data["jobrequirements"])
        $model->jobrequirements = $data["jobrequirements"];
        if($data["workingplace"])
        $model->workingplace = $data["workingplace"];
        if($model->save() >0){
            return Output::Code(200, json_encode($data), "操作成功");
        }else {
            return Output::Code(500, json_encode($data), "操作失败");
        }
       
    }

    /**
     * 创建企业招聘信息
     */
    public function actionSave()
    {
        $data = array(
            "id"=> Request::Post("id"),
            "unitName"=> Request::Post("unitName"),
            "jobName"=> Request::Post("jobName"),
            "mansize"=> Request::Post("mansize"),
            "age"=> Request::Post("age"),
            "record"=> Request::Post("record"),
            "pay"=> Request::Post("pay"),
            "jobrequirements"=> Request::Post("jobrequirements"),
            "workingplace"=> Request::Post("workingplace"),
            "company_id"=> Request::Post("company_id")
        );
        // id', 'unitName', 'jobName', 'mansize', 'age', 'record', 'pay', 'jobrequirements', 'workingplace'
        $model = new RecruitInfo;
        if($data["unitName"])
        $model->unitName = $data["unitName"];
        if($data["jobName"])
        $model->jobName = $data["jobName"];
        if($data["mansize"])
        $model->mansize = $data["mansize"];
        if($data["age"])
        $model->age = $data["age"];
        if($data["record"])
        $model->record = $data["record"];
        if($data["pay"])
        $model->pay = $data["pay"];
        if($data["jobrequirements"])
        $model->jobrequirements = $data["jobrequirements"];
        if($data["workingplace"])
        $model->workingplace = $data["workingplace"];
        if($data["company_id"])
        $model->company_id = $data["company_id"];
        if($model->save() >0){
            return Output::Code(200, "添加成功", "success");
        }else {
            return Output::Code(500, "添加失败", "fail");
        }
       
    }

}
