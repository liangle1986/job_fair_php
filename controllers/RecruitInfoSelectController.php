<?php

namespace app\controllers;
use app\models\RecruitInfo;
use app\models\CompanyFuli;
use app\models\Company;
use app\models\Street;
use app\models\JobInfo;
use app\models\BindAccount;
use app\components\Output;
use app\components\Request;

use Yii;
use yii\data\Pagination;
/**
 * 查询企业岗位信息
 */
class RecruitInfoSelectController extends \yii\web\Controller
{
    public function init(){
        $this->enableCsrfValidation = false;
    }

    public function actionIndex()
    {
        // return $this->render('index');
        //查
        // $sql = "SELECT * FROM RecruitInfo";
        $value = Request::Post("jobName");
        $fuli = Yii::$app->request->post("fuli");
        $street= Yii::$app->request->post("street");
        $jobId =  Yii::$app->request->post("jobId");
        $companyid=Yii::$app->request->post("company_id")?Yii::$app->request->post("company_id"):0;

        $minpay=Yii::$app->request->post("minpay")?Yii::$app->request->post("minpay"):'';
        $maxpay=Yii::$app->request->post("maxpay")?Yii::$app->request->post("maxpay"):'';
        $page=Request::Post("page")?Request::Post("page"):0;

        $sql = "SELECT r. * , ff. fuli,ff.id as fid , cm. id as cid, cm.name,cm.address,cm.phone_call,cm.user_id,cm.remarks,cm.street_id ,cm.area,cm.showno,cm.fuliword, s.id as ssid, s.street_name FROM recruitInfo r LEFT JOIN (SELECT GROUP_CONCAT( fu.fuli SEPARATOR  ';' ) AS fuli, fu.company_id, GROUP_CONCAT( fu.id SEPARATOR  ',' ) AS id FROM ( SELECT l.fuli, f.company_id, l.id FROM fuli l LEFT JOIN company_fuli f ON l.id = f.fuli_id )fu GROUP BY fu.company_id )ff ON r.company_id = ff.company_id LEFT JOIN company cm ON cm.id = r.company_id LEFT JOIN jobInfo j ON r.job_id = j.id LEFT JOIN street s ON s.id = cm.street_id left join company_fuli cf on r.company_id = cf.company_id ";
         

            
        // find()->select(["c.*","r.*","s.*","j.job_name","j.id"]);
        $sql=$sql." where 1=1 ";

        if($minpay!=0){
            $sql=$sql." and r.minpay >= ".$minpay;
        }
        if($maxpay!=0){
            $sql=$sql." and r.maxpay <= ".$maxpay;
        }
       
         if($value){

            $sql=$sql." and (r.jobName like '%".$value."%' or cm.name like '%".$value."%')";
            // $query->where(['in','r.jobName', $value]);

            //福利
            if($fuli){
                $sql= $sql." and cf.fuli_id in (".$fuli.")";
                // $query->andWhere(['in','f.fuli_id' , $fuli]);
            }
            //街道
            if($street){
                $sql= $sql." and cm.street_id in (".$street.")";
                // $query->andWhere(['in','cm.street_id' ,$street]);
            }
            //岗位类型
            if($jobId){
                $sql= $sql." and r.job_id in (".$jobId.")";
                // $query->andWhere(['in','r.job_id' ,$jobId]);
            }
        }elseif($fuli){
            $sql= $sql." and cf.fuli_id in (".$fuli.")";
            // $query->where(['in','f.fuli_id', $fuli]);
            if($street){
                $sql= $sql." and cm.street_id in (".$street.")";
                // $query->andWhere(['in','cm.street_id' , $street]);
            }
             //岗位类型
             if($jobId){
                $sql= $sql." and r.job_id in (".$jobId.")";
                // $query->andWhere(['in','r.job_id', $jobId]);
            }
        }elseif($street){
            $sql=$sql." and cm.street_id in (".$street.")";
            // $query->where(['in', 'cm.street_id', $street]);
             //岗位类型
             if($jobId){
                $sql=$sql." and r.job_id in (".$jobId.")";
                // $query->andWhere(['in','r.job_id', $jobId]);
            }
        }elseif($jobId){//岗位类型
            $sql=$sql." and r.job_id in (".$jobId.")";
            // $query->where(['in','r.job_id' ,$jobId]);
        }elseif($companyid){
            $sql=$sql." and cm.id=".$companyid;
        }
        $sql=$sql." group by r.id order by cm.street_id asc limit ".($page*10)." , 10";
        // $query->groupBy("group by r.id");
   
        $recruit=Yii::$app->db->createCommand($sql)->query();
        $result=Array();
    	if($recruit){
             foreach($recruit as $keys){
                 if($keys['maxpay']!=0 && $keys['minpay']!=0){
                    if($keys['maxpay']==$keys['minpay']){
                        $keys['pay']=$keys['minpay'];
                    }else{
                        $keys['pay']=$keys['minpay'].'-'.$keys['maxpay'];
                    }
                    
                 }else{
                    $keys['pay']="面议";
                 }
                
                array_push($result,$keys);
            }
            
            return Output::Code(200, $result, $sql);
    	}else{
            return Output::code(200, '', "success".$sql);
    	}
    }



     /**
     * 创建企业招聘信息
     */
    public function actionUpdate()
    {
        $data = array(
            "id"=> Request::Post("id"),
            "jobName"=> Request::Post("jobName"),
            "mansize"=> Request::Post("mansize"),
            "pay"=> Request::Post("pay"),
            "age"=> Request::Post("age"),
            "record"=> Request::Post("record"),
            "workingplace"=> Request::Post("workingplace"),
            "work_demand"=> Request::Post("work_demand"),
            "job_id"=>Request::Post("job_id"),
            "company_id"=> Request::Post("company_id")
        );
        if((int)$data["id"]==0){
                $model = new RecruitInfo;
        }else{
                $model = RecruitInfo::findOne((int)$data["id"]);
        }
       
      
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
        if($data["work_demand"])
        $model->work_demand = $data["work_demand"];
        if($data["workingplace"])
        $model->workingplace = $data["workingplace"];
        if($data["job_id"])
        $model->job_id = $data["job_id"];
        if($data["company_id"])
        $model->company_id = $data["company_id"];
        if($model->save() >0){
            return Output::Code(200, "", "更新成功");
        }else {
            return Output::Code(500, "", "更新失败");
        }
       
    }

    public function actionDelrecruit(){
        $data = array(
            "openid"=> Request::Post("openid"),
            "company_id"=> Request::Post("company_id"),
            "recruitid"=> Request::Post("recruitid"), 
        );
        $cdata = BindAccount::find()->where(["user_id" => $data["openid"],"company_id"=>$data["company_id"]])->all();
        $re = array();
        foreach ($cdata as $key ) {
            $re[] = array('company_id' =>$key["company_id"]);
        }
        if(count($re) >0){
            $sql="DELETE FROM `recruitInfo` WHERE `recruitInfo`.`id` =".$data["recruitid"];
            Yii::$app->db->createCommand($sql)->query();
            $sql="DELETE FROM `delivery_info` WHERE `delivery_info`.`recruit_id` =".$data["recruitid"];
            Yii::$app->db->createCommand($sql)->query();
            return Output::Code(200, "", "删除完成");
        }else{
            return Output::Code(500, "", "未授权行为");
        }
    }
}
