<?php

namespace app\controllers;
use app\models\DeliveryInfo;
use app\models\Resume;
use app\components\Output;
use app\components\Request;
use app\components\Qcodemsg;
use Yii;

/**
 * 简历投递信息查询
 */
class DeliveryInfoSelectController extends \yii\web\Controller
{
    public function init(){ $this->enableCsrfValidation = false; }
    public function actionIndex()
    {
        $userId = $_GET["userId"];
        $rid= $_GET["recruitId"];
        $del = DeliveryInfo:: find()->where(["user_id"=> $userId, "recruit_id"=>$rid])->count();
        // $result = array();
        // if($del){
        //     foreach($del as $key){
        //         $map['id']=$keys->id;
        //         $map['resume_id']=$keys->resume_id;
        //         $map['recruit_id']=$keys->recruit_id;
        //         $map['create_time']=$keys->create_time;
        //         $map['status']=$keys->status;
        //         $map['user_id']=$keys->user_id;
        //         array_push($result,$map);
        //     }
        // }
        return Output::Code(200, $del, "success");
    }


    /**
     * 查询排队数量
     */
    public function actionCount(){
        $uid= $_GET["userId"];
        $rid= $_GET["recruitId"];
        $data = DeliveryInfo:: find()->where(["in","status",[1,3,4]])->andWhere(["recruit_id"=> $rid])->orderBy(["create_time"=>"asc"])->all();
        $count = 0;
        $result = array();
        if($data){
            for($i = 0; $i < count($data); $i++){
                if($data[$i]->status == 3){
                    $count++;
                }
                if($data[$i]->user_id == $uid){
                    $map["order"] = $i +1;
                    $map["count"] =  $count;
                    array_push($result, $map);
                    break;
                }
            }
        }
        return Output::Code(200, $result, "success");
    }

    /**
     * 查询录用
     */
    public function actionHireCount(){
        $rid= $_GET["recruitId"];
        $data = DeliveryInfo:: find()->where(["status"=> 4,"recruit_id"=> $rid])->count();
        return Output::Code(200, $data, "success");
    }

    /**
     * 获取企业投递人数
     */
    public function actionRecruitCount(){
        $rid= $_GET["recruitId"];
        $data = DeliveryInfo:: find()->where(["recruit_id"=> $rid])->count();
        return Output::Code(200, $data, "success");
    }

      /**
     * 获取人投递的企业
     */
    public function actionAllCount(){
         $userId = $_GET["userId"];
        $data = DeliveryInfo:: find()->where(["user_id"=> $userId])->groupBy("recruit_id")->count();
        return Output::Code(200, $data, "success");
    }

/**
     * 根据岗位信息修改用户录用状态
     */
    public function actionUpdataResumeStaus(){
        $resId = Request::Get("resId");
        $userId = Request::Get("userId");
        $status = (int)Request::Get("status");
        $data = DeliveryInfo:: find()->where(["user_id"=> $userId, "recruit_id"=> $resId])->all();
        if($data){
            foreach($data as $key) {
                $keys->status = $status;
                $keys->save();
            }
        }
        return Output::Code(200, "修改成功。", "success");
        
    }

    //用户投递
    public function actionUserDelivery(){
        $resumeId=Request::Post("resumeId");
        $recruitId=Request::Post("recruitId");
        $user_id=Request::Post("openid");
        
            if($resumeId!=null && $resumeId!="" && $recruitId!="" && $recruitId!=null && $user_id!=null){
                $count = DeliveryInfo::find()->where(["user_id"=> $user_id,"resume_id"=>$resumeId,"recruit_id"=>$recruitId,"create_time"=>date("Y-m-d 00:00:00")])->count();
       
                if($count!=0){
                    return Output::Code(201, "", "今日已投递");
                }else{
                    $count2=DeliveryInfo::find()->where(["recruit_id"=>$recruitId])->count();
                    $count2++;
                    $model=new DeliveryInfo;
                    $model->resume_id=$resumeId;
                    $model->recruit_id=$recruitId;
                    $model->create_time=date("Y-m-d");
                    $model->status=1;
                    $model->user_id=$user_id;
                    $model->sort=$count2;
                    if($model->save()){

                    }
                    
                    $sql="select c.area,c.showno  from company c left join recruitInfo r on r.company_id=c.id where r.id=".$recruitId." group by c.id";

                    $recruit=Yii::$app->db->createCommand($sql)->query(); 
                    $result = array();
                    if($recruit){
                        foreach($recruit as $keys){
                                $result[]=array(
                                    'area'=>$keys["area"],
                                    'showno'=>$keys["showno"]
                                   
                                );
                        }
                        

                        $getresume=Resume::findOne($resumeId);

                        if($getresume->phone){
                            Qcodemsg::Sendmsg($getresume->phone,0,[$result[0]['area'].$result[0]['showno']]);
                        } 
                        return Output::Code(200, "", "投递成功");
                    }else{
                        return Output::code(404, '', "没有对应的职位");
                    }


                    
                }
            }else{
                return Output::Code(500, "", "缺少参数");
            }
        

        
    }
    //用户查询投递状态
    public function actionUserDelilist(){
        $user_id=Request::Post("openid")?Request::Post("openid"):"0";
        $sql="SELECT ri.jobName,c.name, di.status,di.sort FROM recruitInfo ri LEFT JOIN company c ON ri.company_id = c.id LEFT JOIN delivery_info di ON di.recruit_id=ri.id WHERE di.user_id='".$user_id."' ORDER BY di.id";
        if($user_id!="0"){
            $recruit=Yii::$app->db->createCommand($sql)->query(); 
            $result = array();
            if($recruit){
                foreach($recruit as $keys){
                        $result[]=array(
                            'jobName'=>$keys["jobName"],
                            'name'=>$keys["name"],
                            'status'=>$keys["status"],
                            'sort'=>$keys["sort"]
                        );
                }
                
                return Output::Code(200, $result, "success");
            }else{
                return Output::code(404, '', "没有数据");
            }
        }else{
            return Output::code(500, '', "用户信息参数错误". $user_id);
        }
        
    }
}
