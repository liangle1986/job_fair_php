<?php

namespace app\controllers;
use app\models\Fuli;
use app\models\JobInfo;
use app\models\Street;
use app\components\Output;
use app\components\Request;
class FuliController extends \yii\web\Controller
{
	public function init(){ $this->enableCsrfValidation = false; }
    public function actionIndex()
    {
    	$re=Fuli::find()->all();
    	 $result = array();
    	foreach($re as $keys){
    		$result[]=array(
    			'id'=>$keys['id'],
    			'fuli'=>$keys['fuli']
    		);
    	}
        return Output::Code(200, $result, "success");
    }
    public function actionJob()
    {
        $re=JobInfo::find()->all();
         $result = array();
        foreach($re as $keys){
            $result[]=array(
                'id'=>$keys['id'],
                'job_name'=>$keys['job_name']
            );
        }
        return Output::Code(200, $result, "success");
    }
    public function actionStreet()
    {
        $re=Street::find()->all();
         $result = array();
        foreach($re as $keys){
            $result[]=array(
                'id'=>$keys['id'],
                'street_name'=>$keys['street_name']
            );
        }
        return Output::Code(200, $result, "success");
    }

    /**
     * 创建修改福利
     */
    public function actionFuliSaveOrUpdate(){
        $data=array(
            'id' => Request::Post("id")?Request::Post("id"):0,
            'fuli'=> Request::Post("fuli")
        );

        $model;
        if($data["fuli"]){
            if($data["id"] > 0){
              $model = Fuli::findOne($data["id"]);  
              if($model->fuli == $data["fuli"]){
                return Output::Code(500, $data, "福利没有变化不需要修改");  
              } else{
                $model->fuli = $data["fuli"];
                if($model->save() > 0){
                    return Output::Code(200, $data, "福利修改成功");
                } else {
                    return Output::Code(500, $data, "福利修改失败");
                }
              }
            } else {
                $model = new Fuli;
                $count = Fuli::find()->where(["fuli"=>$data["fuli"]])->count();
                if($count > 0) {
                    return Output::Code(500, $data, "福利不能重复创建");  
                } else {
                    $model->fuli = $data["fuli"];
                    if($model->save() > 0){
                        return Output::Code(200, $data, "福利创建成功");
                    } else {
                        return Output::Code(500, $data, "福利创建失败");
                    }
                }
            }
           
        } else {
            return Output::Code(500, $data, "福利不能为空");  
        }
    }

    /**
     * 创建修改职位类型
     */
    public function actionJobSaveOrUpdate(){
        $data=array(
            'id' => Request::Post("id")?Request::Post("id"):0,
            'job_name'=> Request::Post("job_name"),
            'remarks'=> Request::Post("remarks")
        );

        $model;
        if($data["job_name"]){
            if($data["id"] > 0){
              $model = JobInfo::findOne($data["id"]);  
              if($model->job_name == $data["job_name"]){
                return Output::Code(500, $data, "职务类型没有变化不需要修改");  
              } else{
                $model->job_name = $data["job_name"];
                $model->remarks = $data["remarks"];
                if($model->save() > 0){
                    return Output::Code(200, $data, "职务类型修改成功");
                } else {
                    return Output::Code(500, $data, "职务类型修改失败");
                }
              }
            } else {
                $model = new JobInfo;
                $count = JobInfo::find()->where(["job_name"=>$data["job_name"]])->count();
                if($count > 0) {
                    return Output::Code(500, $data, "职务类型不能重复创建");  
                } else {
                    $model->job_name = $data["job_name"];
                    $model->remarks = $data["remarks"];
                    if($model->save() > 0){
                        return Output::Code(200, $data, "职务类型创建成功");
                    } else {
                        return Output::Code(500, $data, "职务类型创建失败");
                    }
                }
            }
           
        } else {
            return Output::Code(500, $data, "职务类型不能为空");  
        }
    }
}
