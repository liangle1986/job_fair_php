<?php

namespace app\controllers;
use app\models\RecruitInfo;
use app\components\Output;
use app\components\Request;
/**
 * 根据企业查询岗位
 */
class RecruitbyidController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $id = Request::Get("companyId");
        // id', 'unitName', 'jobName', 'mansize', 'age', 'record', 'pay', 'jobrequirements', 'workingplace'
        $query = RecruitInfo::find();
        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $query->count(),
        ]);
        $countries = $query
            ->where(['company_id'=>$id])
            ->orderBy('jobName')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
           
        $recruit = [
            'countries' => $countries,
            'pagination' => $pagination,
        ];  
        Output::Code(200, $recruit, "success"); 
        // $result = array();
    	// if($model){
        //     // foreach($model as $keys){
        //     //         $map['id']=$keys->id;
        //     //         $map['unitName']=$keys->unitName;
        //     //         $map['jobName']=$keys->jobName;
        //     //         $map['mansize']=$keys->mansize;
        //     //         $map['age']=$keys->age;
        //     //         $map['record']=$keys->record;
        //     //         $map['pay']=$keys->pay;
        //     //         $map['jobrequirements']=$keys->jobrequirements;
        //     //         $map['workingplace']=$keys->workingplace;
        //     //         array_push($result,$map);
        //     // }
        //     Output::Code(200, $model, "success");
        // } else {
        //     Output::Code(200, "", "success");
        // }
    }

}
