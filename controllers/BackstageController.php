<?php

namespace app\controllers;
use app\components\Output;
use app\components\Request;
use app\models\Company;
use app\models\RecruitInfo;
use app\models\DeliveryInfo;
use app\models\CompanyFuli;
use app\models\Street;
use app\models\Resume;
use app\models\Admin;
use app\models\Fuli;
use app\models\BindAccount;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use yii\filters\ContentNegotiator; 
use yii\web\Response; 
use Yii;
use yii\data\Pagination;
/**
 * pc后端功能实现类
 */
class BackstageController extends \yii\web\Controller
{
    public function init(){ $this->enableCsrfValidation = false; }

		public function behaviors()
{
    return ArrayHelper::merge([
        [
            'class' => Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['*'],
            ],
            'actions' => [
                'login' => [
                    'Access-Control-Allow-Credentials' => true,
                ]
            ]
        ],
    ], parent::behaviors());
}

    public function actionIndex()
    {
        return $this->render('index');
    }


    /**
     * 获取admin的参数
     */
    function getAdminData(){

        $data =[            
            "id" => Yii::$app->request->post("id")?Yii::$app->request->post("id"):"0",
            "username" => Request::Post("username"),
            "password" => Request::Post("password"),
            "auth" => Yii::$app->request->post("auth")?Yii::$app->request->post("auth"):""
        ];
        return $data;
    }

    /**
     * 查询登陆用户信息
     */
    public function actionAdmin(){
        $pageType = Request::Post("type")?Request::Post("type"):0;
        $data =$this->getAdminData();
        if($pageType== 0){
            $query = Admin::find();
            
            if($data["username"] != null){
                $query -> where(["like", "username", $data["username"]]);
            }
            $pagination = new Pagination([
                'defaultPageSize' => 20,
                'totalCount' => $query->count(),
            ]);
        
            $countries = $query->orderBy("id")
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->asArray()
                ->all();

            $recruit = [
                'countries' => $countries,
                'pagination' => $pagination,
            ];
                return Output::Code(200, $recruit, "success");
        } else {
            $sql = "select * from admin ";
            if($data["username"] != null){
                $sql = $sql." where username like '%".$data["username"]."%'";
            }
            $result=Fuli::findBySql($sql)->asArray()->all();
            return Output::Code(200, $result, "success");
        }
    }
    /**
     * 根据用户名密码查询用户信息
     */
    public function actionSelectAdmin(){
      
        $data =$this->getAdminData();
        
        $model=null;
        if((int)$data["id"] > 0){
            $model= Admin::findOne($data["id"]);
            if($model != null && $model != "") {
                return Output::Code(200, $model, "查询成功");
            } else {
                return Output::Code(500, $data, "用户标示不存在");
            }
        } elseif($data["username"] !=null && $data["username"] != "" && $data["password"] !=null && $data["password"] != ""){
            $model= Admin::findBySql('SELECT * FROM admin')->where(["username" => $data["username"], "password" => md5($data["password"])])->asArray()->one();
            if($model) {
                $model["password"] = md5($model["password"].date("Ymd"));
                return Output::Code(200, $model, "查询成功");
            } else {
                return Output::Code(500, $data, "用户名或密码错误");
            }
        }else {
            return Output::Code(500, $data, "用户名和密码不能为空");
        }

    }

    function get_user($username, $password){
        if($username !=null && $username != "" && $dpassword !=null && $password != ""){
            $model= Admin::findBySql('SELECT * FROM admin')->where(["username" => $username, "password" => md5($password)])->one();
            if($model != null && $model != "") {
              return true;
            } else {
                return false;
            }
        }
       return false;
    }

    /**
     * 创建/修改用户信息
     */
    public function actionSaveOrUpdateAdmin(){
        $data =$this->getAdminData();
        $model=null;
        if((int)$data["id"] > 0){
            $model = Admin::findOne((int)$data["id"]);
        }else {
            $model= new Admin;
        }
        if($data["username"])
        $model->username = $data["username"];
        if($data["password"])
        $model->password = md5($data["password"]);
        if($data["auth"])
        $model->auth = $redatas["auth"];
        if($model->save() > 0){
            return Output::Code(200, $model, "编辑成功");
        } else {
            return Output::Code(500,  $data, "编辑失败");
        }
    }

    /**
     * 删除用户信息
     */
    public function actionDelectAdmin(){
        $data =$this->getAdminData();
        if($data["username"] !=null && $data["username"] != "" && $data["password"] !=null && $data["password"] != ""){
            $model= Admin::findBySql('SELECT * FROM admin')->where(["username" => $data["username"], "password" => md5($data["password"])])->asArray()->one();
            if($model != null && $model != "") {
                if((int)$data["id"] > 0){
                    $model= Admin::findOne($data["id"]);
                    if($model != null && $model != "") {
                        $model= Admin::deleteAll(["id" =>$data["id"]]);
                        return Output::Code(200, $model, "删除成功");
                    } else {
                        return Output::Code(500, $data, "要删除的用户不存在");
                    }
                }
            } else {
                return Output::Code(500, $data, "用户名或密码错误,不能进行删除");
            }
        }else {
            return Output::Code(500, $recruit, "用户名和密码不能为空");
        }
    }



    /**
     * 获取简历参数
     * id`, `url_id`, `username`, `sex`, `identitycard`, `birthday`, `education`, 
     * `province`, `city`, `county`, `place`, `domicile`, `phone`, `status`, `remark`, `userId`, `resume_code`, `record_date`, 
     * `nation`, `marital_status`, `home_phone`, `personnel_type`, `Job_intention`, `expected_income`, `political_status`, 
     * `street`, `person_height`, `leparray_data`, `workarray_data`
     */
    function get_resumeData(){
        $data =[            
            "id" => Request::Post("id")?Request::Post("id"):"0",
            "url_id" => Request::Post("url_id"),
            "username" => Request::Post("username"),
            "sex" => Request::Post("sex"),
            "identitycard" => Request::Post("identitycard"),
            "birthday" => Request::Post("birthday"),
            "education" => Request::Post("education"),
            "province" => Request::Post("province"),
            "city" => Request::Post("city"),
            "county" => Request::Post("county"),
            "place" => Request::Post("place"),
            "domicile" => Request::Post("domicile"),
            "phone" => Request::Post("phone"),
            "status" => Request::Post("status")?Request::Post("status"):"0",
            "remark" => Request::Post("remark"),
            "userId" => Request::Post("userId"),
            "resume_code" => Request::Post("resume_code"),
            "record_date" => Request::Post("record_date"),
            "nation" => Request::Post("nation"),
            "marital_status" => Request::Post("marital_status"),
            "home_phone" => Request::Post("home_phone"),
            "personnel_type" => Request::Post("personnel_type"),
            "Job_intention" => Request::Post("Job_intention"),
            "expected_income" => Request::Post("expected_income"),
            "political_status" => Request::Post("political_status"),
            "street" => Request::Post("street"),
            "person_height" => Request::Post("person_height"),
            "leparray_data" => Request::Post("leparray_data"),
            "workarray_data" => Request::Post("workarray_data")
        ];
        return $data;
    }

    /**
     * 查询简历
     */
    public function actionSelectResume(){
        $data =$this->get_resumeData();
        $query=Resume::find();

        $sql = "select * from resume";
        $wOra = true;
        if($data["username"]) {
            // $sql = $sql.$wOra." username like '%".$data["username"]."%'";
            if($wOra){
                $query->where(["like","username",$data["username"]]);
            }
            $wOra = false; 
           

        }
        if($data["url_id"]) {
            // $sql = $sql.$wOra." url_id ='".$data["url_id"]."'";
            if($wOra){
                $query->where(["url_id"=>$data["url_id"]]);
            } else {
                $query->andWhere(["url_id"=>$data["url_id"]]);
            }
            $wOra = false; 
        }
        if($data["sex"]) {
            // $sql = $sql.$wOra." sex =".(int)$data["sex"];
            if($wOra){
                $query->where(["sex"=>(int)$data["sex"]]);
            } else {
                $query->andWhere(["sex"=>(int)$data["sex"]]);
            }
            $wOra = false; 
        }
        if($data["identitycard"]) {
            $sql = $sql.$wOra." identitycard ='".$data["identitycard"]."'";
            if($wOra){
                $query->where(["identitycard"=>$data["identitycard"]]);
            } else {
                $query->andWhere(["identitycard"=>$data["identitycard"]]);
            }
            $wOra = false; 
        }

        if($data["birthday"]) {
            $sql = $sql.$wOra." birthday ='".(int)$data["birthday"]."'";
            $wOra = " and "; 
        }
        if($data["education"]) {
            $sql = $sql.$wOra." education ='".$data["education"]."'";
            if($wOra){
                $query->where(["education"=>$data["education"]]);
            } else {
                $query->andWhere(["education"=>$data["education"]]);
            }
            $wOra = false; 
        }
        if($data["province"]) {
            $sql = $sql.$wOra." province ='".$data["province"]."'";
            if($wOra){
                $query->where(["province"=>$data["province"]]);
            } else {
                $query->andWhere(["province"=>$data["province"]]);
            }
            $wOra = false; 
        }
        if($data["city"]) {
            $sql = $sql.$wOra." city ='".$data["city"]."'";
            if($wOra){
                $query->where(["city"=>$data["city"]]);
            } else {
                $query->andWhere(["city"=>$data["city"]]);
            }
            $wOra = false; 
        }
        if($data["county"]) {
            $sql = $sql.$wOra." county ='".$data["county"]."'";
            if($wOra){
                $query->where(["county"=>$data["county"]]);
            } else {
                $query->andWhere(["county"=>$data["county"]]);
            }
            $wOra = false; 
        }
        if($data["place"]) {
            $sql = $sql.$wOra." place ='".$data["place"]."'";
            if($wOra){
                $query->where(["place"=>$data["place"]]);
            } else {
                $query->andWhere(["place"=>$data["place"]]);
            }
            $wOra = false; 
        }
        if($data["domicile"]) {
            $sql = $sql.$wOra." domicile ='".$data["domicile"]."'";
            if($wOra){
                $query->where(["domicile"=>$data["domicile"]]);
            } else {
                $query->andWhere(["domicile"=>$data["domicile"]]);
            }
            $wOra = false; 
        }
        if($data["phone"]) {
            $sql = $sql.$wOra." phone ='".$data["phone"]."'";
            if($wOra){
                $query->where(["phone"=>$data["phone"]]);
            } else {
                $query->andWhere(["phone"=>$data["phone"]]);
            }
            $wOra = false; 
        }
        if((int)$data["status"] > 0) {
            $sql = $sql.$wOra." status ='".(int)$data["status"]."'";
            if($wOra){
                $query->where(["status"=>(int)$data["status"]]);
            } else {
                $query->andWhere(["status"=>(int)$data["status"]]);
            }
            $wOra = false; 
        }
        if($data["remark"]) {
            $sql = $sql.$wOra." remark ='".$data["remark"]."'";
            if($wOra){
                $query->where(["remark"=>$data["remark"]]);
            } else {
                $query->andWhere(["remark"=>$data["remark"]]);
            }
            $wOra = false; 
        }
        if($data["userId"]) {
            $sql = $sql.$wOra." userId ='".$data["userId"]."'";
            if($wOra){
                $query->where(["userId"=>$data["userId"]]);
            } else {
                $query->andWhere(["userId"=>$data["userId"]]);
            }
            $wOra = false; 
        }
        if($data["resume_code"]) {
            $sql = $sql.$wOra." resume_code ='".$data["resume_code"]."'";
            if($wOra){
                $query->where(["resume_code"=>$data["resume_code"]]);
            } else {
                $query->andWhere(["resume_code"=>$data["resume_code"]]);
            }
            $wOra = false; 
        }
        if($data["record_date"]) {
            $sql = $sql.$wOra." record_date ='".$data["record_date"]."'";
            if($wOra){
                $query->where(["record_date"=>$data["record_date"]]);
            } else {
                $query->andWhere(["record_date"=>$data["record_date"]]);
            }
            $wOra = false; 
        }
        if($data["home_phone"]) {
            $sql = $sql.$wOra." home_phone ='".$data["home_phone"]."'";
            if($wOra){
                $query->where(["home_phone"=>$data["home_phone"]]);
            } else {
                $query->andWhere(["home_phone"=>$data["home_phone"]]);
            }
            $wOra = false; 
        }
        if($data["personnel_type"]) {
            $sql = $sql.$wOra." personnel_type ='".$data["personnel_type"]."'";
            if($wOra){
                $query->where(["personnel_type"=>$data["personnel_type"]]);
            } else {
                $query->andWhere(["personnel_type"=>$data["personnel_type"]]);
            }
            $wOra = false; 
        }
        if($data["Job_intention"]) {
            $sql = $sql.$wOra." Job_intention ='".$data["Job_intention"]."'";
            if($wOra){
                $query->where(["Job_intention"=>$data["Job_intention"]]);
            } else {
                $query->andWhere(["Job_intention"=>$data["Job_intention"]]);
            }
            $wOra = false; 
        }
        if($data["expected_income"]) {
            $sql = $sql.$wOra." expected_income ='".$data["expected_income"]."'";
            if($wOra){
                $query->where(["expected_income"=>$data["expected_income"]]);
            } else {
                $query->andWhere(["expected_income"=>$data["expected_income"]]);
            }
            $wOra = false; 
        }
        if($data["political_status"]) {
            $sql = $sql.$wOra." political_status ='".$data["political_status"]."'";
            if($wOra){
                $query->where(["political_status"=>$data["political_status"]]);
            } else {
                $query->andWhere(["political_status"=>$data["political_status"]]);
            }
            $wOra = false; 
        }
        if($data["street"]) {
            $sql = $sql.$wOra." street ='".$data["street"]."'";
            if($wOra){
                $query->where(["street"=>$data["street"]]);
            } else {
                $query->andWhere(["street"=>$data["street"]]);
            }
            $wOra = false; 
        }
        if($data["person_height"]) {
            $sql = $sql.$wOra." person_height ='".$data["person_height"]."'";
            if($wOra){
                $query->where(["person_height"=>$data["person_height"]]);
            } else {
                $query->andWhere(["person_height"=>$data["person_height"]]);
            }
            $wOra = false; 
        }
        if($data["leparray_data"]) {
            $sql = $sql.$wOra." leparray_data ='".$data["leparray_data"]."'";
            if($wOra){
                $query->where(["leparray_data"=>$data["leparray_data"]]);
            } else {
                $query->andWhere(["leparray_data"=>$data["leparray_data"]]);
            }
            $wOra = false; 
        }
        if($data["workarray_data"]) {
            $sql = $sql.$wOra." workarray_data ='".$data["workarray_data"]."'";
            if($wOra){
                $query->where(["workarray_data"=>$data["workarray_data"]]);
            } else {
                $query->andWhere(["workarray_data"=>$data["workarray_data"]]);
            }
            $wOra = false; 
        }

        $sql = $sql." order by id";
        // BySql($sql);
        $pagination = new Pagination([
            'defaultPageSize' => 20,
            'totalCount' => $query->count(),
        ]);
    
        $countries = $query->orderBy("id")
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
           
        $recruit = [
            'countries' => $countries,
            'pagination' => $pagination,
        ];
  
        return Output::Code(200, $recruit, "查询成功");
       
    }

    /**
     * 新增修改简历
     */
    public function actionSaveOrUpdateResume()
    {
        $data =$this->get_resumeData();
        try{
            $model = null;
           if($data["id"] > 0){
            $model = Resume::findOne((int)$data["id"]);
           } else {
            $model = new Resume;
           }
           
            if( $data["url_id"])
            $model->url_id =  $data["url_id"];
            if( $data["username"])
            $model->username =  $data["username"];
            if( $data["sex"])
            $model->sex = (int) $data["sex"];
            if( $data["identitycard"])
            $model->identitycard =  $data["identitycard"];
            if($data["birthday"])
            $model->birthday = (int)$data["birthday"];
            if($data["education"])
            $model->education = (int)$data["education"];
            if($data["province"])
            $model->province = $data["province"];
            if($data["city"])
            $model->city = $data["city"];
            if($data["county"])
            $model->county = $data["county"];
            if($data["place"])
            $model->place = $data["place"];
            if($data["domicile"])
            $model->domicile = $data["domicile"];
            if($data["phone"])
            $model->phone = $data["phone"];
            if($data["status"])
            $model->status = $data["status"];
            if($data["remark"])
            $model->remark = $data["remark"];
            if($data["userId"])
            $model->userId = $data["userId"];

            if($data["resume_code"])
            $model->resume_code = $data["resume_code"];
            if($data["record_date"])
            $model->record_date = $data["record_date"];
            if($data["nation"])
            $model->nation = $data["nation"];
            if($data["marital_status"])
            $model->marital_status = $data["marital_status"];
            if($data["home_phone"])
            $model->home_phone = $data["home_phone"];
            if($data["personnel_type"])
            $model->personnel_type = $data["personnel_type"];
            if($data["Job_intention"])
            $model->Job_intention = $data["Job_intention"];
            if($data["expected_income"])
            $model->expected_income = $data["expected_income"];
            if($data["political_status"])
            $model->political_status = $data["political_status"];
            if($data["street"])
            $model->street = $data["street"];
            if($data["person_height"])
            $model->person_height = $data["person_height"];
            if($data["workarray_data"])
            $model->workarray_data=json_encode($data["workarray_data"]);
            if($data["leparray_data"])
            $model->leparray_data=json_encode($data["leparray_data"]);
            if($model->save()>0) {
                return Output::Code(200, $model, "编辑成功"); 
            } else {
                return Output::Code(500, $model, "编辑失败");  
            }
        }catch(Exception $e){
            return Output::Code(404, $data,  "编辑失败"); 
        }
      
    }


    /**
     * 删除简历
     */
    public function actionDeleteResume()
    {
        $data =$this->getAdminData();
        if($data["username"] !=null && $data["username"] != "" && $data["password"] !=null && $data["password"] != ""){
            $boo = $this->get_user($data["username"],$data["password"]);
            if($boo) {
                if((int)$data["id"] > 0){
                    $model= Resume::findOne($data["id"]);
                    if($model != null && $model != "") {
                        $model= Resume::deleteAll(["id" =>$data["id"]]);
                        return Output::Code(200, $model, "删除成功");
                    } else {
                        return Output::Code(500, $data, "要删除的简历不存在");
                    }
                }
            } else {
                return Output::Code(500, $data, "用户名或密码错误,不能进行删除");
            }
        }else {
            return Output::Code(500, "", "用户名和密码不能为空");
        }
        
    }



    /**
     * 获取街道信息
     */
    function get_streetData(){
        $data =[            
            "id" => Request::Post("id")?Request::Post("id"):"0",
            "street_name" => Request::Post("street_name"),
            "tip" => Request::Post("tip")
        ];
        return $data;
    }

    /**
     * 查询街道
     */
    public function actionStreet()
    {
        $data = $this->get_streetData();
        $pageType = Request::Post("type")?Request::Post("type"):0;

        if($pageType== 0){
            $query = Street::find();
            
            if($data["street_name"] != null){
                $query -> where(["like", "street_name", $data["street_name"]]);
            }
            $pagination = new Pagination([
                'defaultPageSize' => 20,
                'totalCount' => $query->count(),
            ]);
        
            $countries = $query->orderBy("id")
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->asArray()
                ->all();

            $recruit = [
                'countries' => $countries,
                'pagination' => $pagination,
            ];
                return Output::Code(200, $recruit, "success");
        } else {
            $sql = "select * from street ";
            if($data["street_name"] != null){
                $sql = $sql." where street_name like '%".$data["street_name"]."%'";
            }
            $result=Street::findBySql($sql)->asArray()->all();
            //  $result = array();
            // foreach($re as $keys){
            //     $result[]=array(
            //         'id'=>$keys['id'],
            //         'street_name'=>$keys['street_name']
            //     );
            // }
            return Output::Code(200, $result, "success");
        }
        
    }

    /**
     * 新增修改街道
     */
    public function actionSaveOrUpdateStreet()
    {
        $data = $this->get_streetData();
        $model = null;
        if($data["id"]>0){
            $model = Street::findOne($data["id"]);
        } else {
            $model = new Street;
        }
        if($data["street_name"])
        $model->street_name =  $data["street_name"];
        if( $data["tip"])
        $model->tip =  $data["tip"];
        if($model->save()>0) {
            return Output::Code(200, $model, "编辑成功"); 
        } else {
            return Output::Code(500, $model, "编辑失败");  
        }
    }


    /**
     * 删除街道
     */
    public function actionDeleteStreet()
    {
        $data =$this->getAdminData();
        if($data["username"] !=null && $data["username"] != "" && $data["password"] !=null && $data["password"] != ""){
            $boo = $this->get_user($data["username"],$data["password"]);
            if($boo) {
                if((int)$data["id"] > 0){
                    $model= Street::findOne($data["id"]);
                    if($model != null && $model != "") {
                        $model= Street::deleteAll(["id" =>$data["id"]]);
                        return Output::Code(200, $model, "删除成功");
                    } else {
                        return Output::Code(500, $data, "要删除的街道不存在");
                    }
                }
            } else {
                return Output::Code(500, $data, "用户名或密码错误,不能进行删除");
            }
        }else {
            return Output::Code(500, $recruit, "用户名和密码不能为空");
        }
        
    }




     /**
     * 获取福利信息
     */
    function get_fuliData(){
        $data =[            
            "id" => Request::Post("id")?Request::Post("id"):"0",
            "fuli" => Request::Post("fuli")
        ];
        return $data;
    }

    /**
     * 查询福利
     */
    public function actionfuli()
    {
        $data = $this->get_streetData();
        $pageType = Request::Post("type")?Request::Post("type"):0;

        if($pageType== 0){
            $query = Fuli::find();
            
            if($data["fuli"] != null){
                $query -> where(["like", "fuli", $data["fuli"]]);
            }
            $pagination = new Pagination([
                'defaultPageSize' => 20,
                'totalCount' => $query->count(),
            ]);
        
            $countries = $query->orderBy("id")
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->asArray()
                ->all();

            $recruit = [
                'countries' => $countries,
                'pagination' => $pagination,
            ];
                return Output::Code(200, $recruit, "success");
        } else {
            $sql = "select * from fuli ";
            if($data["fuli"] != null){
                $sql = $sql." where fuli like '%".$data["fuli"]."%'";
            }
            $result=Fuli::findBySql($sql)->asArray()->all();
            return Output::Code(200, $result, "success");
        }
    }

    /**
     * 新增修改福利
     */
    public function actionSaveOrUpdateFuli()
    {
        $data = $this->get_fuliData();
        $model = null;
        if($data["id"]>0){
            $model = Fuli::findOne($data["id"]);
        } else {
            $model = new Fuli;
        }
        if($data["fuli"])
        $model->fuli =  $data["fuli"];
        if($model->save()>0) {
            return Output::Code(200, $model, "编辑成功"); 
        } else {
            return Output::Code(500, $model, "编辑失败");  
        }
    }


    /**
     * 删除福利
     */
    public function actionDeleteFuli()
    {
        $data =$this->getAdminData();
        if($data["username"] !=null && $data["username"] != "" && $data["password"] !=null && $data["password"] != ""){
            $boo = $this->get_user($data["username"],$data["password"]);
            if($boo) {
                if((int)$data["id"] > 0){
                    $model= Fuli::findOne($data["id"]);
                    if($model != null && $model != "") {
                        $model= Fuli::deleteAll(["id" =>$data["id"]]);
                        return Output::Code(200, $model, "删除成功");
                    } else {
                        return Output::Code(500, $data, "要删除的福利不存在");
                    }
                }
            } else {
                return Output::Code(500, $data, "用户名或密码错误,不能进行删除");
            }
        }else {
            return Output::Code(500, $recruit, "用户名和密码不能为空");
        }
        
    }

    /**
     * 获取单位信息
     */
    function get_company(){
        $data =[        
            "id" => Request::Post("id")?(int)Request::Post("id"):0,
            "name" => Request::Post("name")?Request::Post("name"):"",
            "address" => Request::Post("address")?Request::Post("address"):"",
            "phone_call" => Request::Post("phone_call")?Request::Post("phone_call"):"",
            "user_id" => Request::Post("user_id"),
            "remarks" => Request::Post("remarks")?Request::Post("remarks"):"",
            "street_id"=>Request::Post("streetId")?Request::Post("streetId"):0,
            "area"=>Request::Post("area"),
            "showno"=>Request::Post("showno")
        ];
        return $data;
    }


     /**
     * 查询企业列表
     */
    public function actionCompany()
    {
        $page=Request::Post("page")?Request::Post("page"):0;
        $data = $this->get_company();
  
        $sql="SELECT * FROM company ";
        $query=Company::find();
        $wOra = true;
        if($data["name"] !=null && $data["name"] !="") {
            // $sql = $sql.$wOra." name like '%".$data["name"]."%'";
            $tsql = ["name"=>$data["name"]];
            if($wOra){
                $query->where($tsql);
            } else {
                $query->andWhere($tsql);
            }
            $wOra = false; 
        }
        if($data["address"]) {
            $sql = $sql.$wOra." address like '%".$data["address"]."%'";
            $tsql = ["address"=>$data["address"]];
            if($wOra){
                $query->where($tsql);
            } else {
                $query->andWhere($tsql);
            }
            $wOra = false; 
        }
    
        // $query=Company::findBySql($sql);

        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $query->count(),
        ]);

        $countries = $query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
            
        $recruit = [
            'countries' => $countries,
            'pagination' => $pagination,
        ];
            
        return Output::Code(200, $recruit, "success");
    }

     /**
     * 创建修改企业信息
     */
    public function actionSaveOrUpdateCompany(){
        $data = $this->get_company();
        if($data["id"] > 0){
            $model = Company::findOne($data["id"]);
        } else {
            $model = new Company;
        }
      
        if($data["name"])
        $model->name = $data["name"];
        if($data["address"])
        $model->address = $data["address"];
        if($data["phone_call"])
        $model->phone_call = $redatas["phone_call"];
        if($data["user_id"])
        $model->user_id = $data["user_id"];
        if($data["remarks"])
        $model->remarks = $data["remarks"];
        if($data["street_id"])
        $model->street_id = (int)$data["street_id"];
        if($data["area"])
        $model->area = $data["area"];
        if($data["showno"])
        $model->showno = (int)$data["showno"];
        if($model->save() > 0){
            return Output::Code(200, $model, "编辑成功");
        } else {
            return Output::Code(500, $data, "编辑失败");
        }
      
    }


     /**
     * 删除单位
     */
    public function actionDeleteCompany()
    {
        $data =$this->getAdminData();
        if($data["username"] !=null && $data["username"] != "" && $data["password"] !=null && $data["password"] != ""){
            $boo = $this->get_user($data["username"],$data["password"]);
            if($boo) {
                if((int)$data["id"] > 0){
                    $model= Company::findOne($data["id"]);
                    if($model != null && $model != "") {
                        $model= Company::deleteAll(["id" =>$data["id"]]);
                        return Output::Code(200, $model, "删除成功");
                    } else {
                        return Output::Code(500, $data, "要删除的单位不存在");
                    }
                }
            } else {
                return Output::Code(500, $data, "用户名或密码错误,不能进行删除");
            }
        }else {
            return Output::Code(500, $recruit, "用户名和密码不能为空");
        }
        
    }

    /**
     * 获取企业福利信息
     */

    function get_comFuliData(){
        return $data = [
            "company_id" => Request::Post("company_id")?Request::Post("company_id"):"0",
            "fuli_id" => Request::Post("fuli_id")?Request::Post("fuli_id"):"0",
            "name" => Request::Post("name"),
            "fuli" => Request::Post("fuli")
        ];
    }

    /**
     * 查询企业福利
     */
    public function actionCompanyFuli(){

        $data = $this->get_comFuliData();
        $pageType = Request::Post("type")?Request::Post("type"):0;

        if($pageType== 0){
            $query = CompanyFuli::find()->alias("f")->
            select(["f.company_id","f.fuli_id","c.name","l.fuli"])
            ->leftJoin("company c","`f`.`company_id` = `c`.`id`")
            ->leftJoin("fuli l", "`f`.`fuli_id` = `l`.`id`");
            $psAnd = true;
            if($data["fuli"] != null){
                $sql = ["like","l.fuli", $data["fuli"]];
                $query->Where($sql);
                $psAnd = false;
            }
            if($data["name"] != null){
                $sql = ["like","c.name", $data["name"]];
                if($psAnd){
                    $query->Where($sql);
                } else{
                    $query->andWhere($sql);
                }
                
                $psAnd = false;
            }
            if($data["company_id"] > 0){
                $sql = ["f.company_id"=>$data["company_id"]];
                if($psAnd){
                    $query->Where($sql);
                } else{
                    $query->andWhere($sql);
                }
                
                $psAnd = false;
            }
            $pagination = new Pagination([
                'defaultPageSize' => 20,
                'totalCount' => $query->count(),
            ]);
        
            $countries = $query
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->asArray()
                ->all();

            $recruit = [
                'countries' => $countries,
                'pagination' => $pagination,
            ];
                return Output::Code(200, $recruit, "success");
        } else {
            $sql = "SELECT f.company_id,f.fuli_id,c.name,l.fuli FROM company_fuli f left JOIN company c on f.company_id = c.id LEFT JOIN fuli l on f.fuli_id = l.id ";
            $psAnd = " Where ";
            if($data["fuli"] != null){
                $sql = $sql.$psAnd." l.fuli like '%".$data["fuli"]."%'";
                $psAnd = " and ";
            }
            if($data["name"] != null){
                $sql = $sql.$psAnd." c.name like '%".$data["name"]."%'";
                $psAnd = " and ";
            }
            if($data["company_id"] > 0){
                $sql = $sql.$psAnd." f.company_id =".$data["company_id"];
                $psAnd = " and ";
            }
            $result=CompanyFuli::findBySql($sql)->asArray()->all();
            return Output::Code(200, $result, "success");
        }
    }

    /**
     * 新增修改单位福利
     */
    public function actionSaveOrUpdateCompanyFuli()
    {
        $data = $this->get_comFuliData();
        $model = CompanyFuli::find()->where(["company_id"=>$data["company_id"], "fuli_id"=>$data["fuli_id"]])->one();
        if($model){
            return Output::Code(200, $model, "编辑成功"); 
        } else {
            $model = new CompanyFuli;
        }
        if($data["company_id"])
        $model->company_id =  $data["company_id"];
        if($data["fuli_id"])
        $model->fuli_id =  $data["fuli_id"];
        
        if($model->save()>0) {
            return Output::Code(200, $model, "编辑成功"); 
        } else {
            return Output::Code(500, $model, "编辑失败");  
        }
    }


    /**
     * 删除单位福利
     */
    public function actionDeleteCompanyFuli()
    {
        $data =$this->getAdminData();
        if($data["username"] !=null && $data["username"] != "" && $data["password"] !=null && $data["password"] != ""){
            $boo = $this->get_user($data["username"],$data["password"]);
            if($boo) {
                if((int)$data["id"] > 0){
                    $count= CompanyFuli::find()->where(["company_id"=>$data["id"]])->count();
                    if($count > 0) {
                        $model= CompanyFuli::deleteAll(["company_id" =>$data["id"]]);
                        return Output::Code(200, $model, "删除成功");
                    } else {
                        return Output::Code(500, $data, "要删除的单位福利不存在");
                    }
                }
            } else {
                return Output::Code(500, $data, "用户名或密码错误,不能进行删除");
            }
        }else {
            return Output::Code(500, $recruit, "用户名和密码不能为空");
        }
        
    }


     /**
     * 获取绑定信息
     */
    function get_bindData(){
        return $data = [
            "id" => Request::Post("id")?Request::Post("id"):"0",
            "company_id" => Request::Post("company_id")?Request::Post("company_id"):"0",
            "user_id" => Request::Post("user_id"),
            "username" => Request::Post("username"),
            "comname" => Request::Post("comname")
        ];
    }

     /**
     * 查询绑定信息
     */
    public function actionBindAccount(){

        $data = $this->get_bindData();
        $pageType = Request::Post("type")?Request::Post("type"):0;

        if($pageType== 0){
            $query = BindAccount::find()->alias("b")
            ->select(["b.id","b.company_id","b.user_id","c.name","u.username"])
            ->leftJoin("company c", "`b`.`company_id` =`c`.`id`")
            ->leftJoin("user u", "`b`.`user_id` = `u`.`openid`");
            $psAnd = true;
            if($data["username"] != null){
                $sql = ["like","u.username", $data["username"]];
                $query->Where($sql);
                $psAnd = false;
            }
            if($data["comname"] != null){
                $sql = ["like","c.name", $data["comname"]];
                if($psAnd){
                    $query->Where($sql);
                } else{
                    $query->andWhere($sql);
                }
                
                $psAnd = false;
            }
            if($data["company_id"] > 0){
                $sql = ["b.company_id"=>$data["company_id"]];
                if($psAnd){
                    $query->Where($sql);
                } else{
                    $query->andWhere($sql);
                }
                
                $psAnd = false;
            }
            if($data["user_id"] > 0){
                $sql = ["b.user_id"=>$data["user_id"]];
                if($psAnd){
                    $query->Where($sql);
                } else{
                    $query->andWhere($sql);
                }
                
                $psAnd = false;
            }
            $pagination = new Pagination([
                'defaultPageSize' => 20,
                'totalCount' => $query->count(),
            ]);
        
            $countries = $query
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->asArray()
                ->all();

            $recruit = [
                'countries' => $countries,
                'pagination' => $pagination,
            ];
                return Output::Code(200, $recruit, "success");
        } else {
            $sql = "SELECT b.id,b.company_id,b.user_id,c.name,u.username FROM bind_account b LEFT JOIN company c on b.company_id =c.id LEFT JOIN user u ON b.user_id = u.openid";
            $psAnd = " Where ";
            if($data["username"] != null){
                $sql = $sql.$psAnd." u.username like '%".$data["username"]."%'";
                $psAnd = " and ";
            }
            if($data["comname"] != null){
                $sql = $sql.$psAnd." c.name like '%".$data["comname"]."%'";
                $psAnd = " and ";
            }

            if($data["company_id"] > 0){
                $sql = $sql.$psAnd." b.company_id =".$data["company_id"];
                $psAnd = " and ";
            }
            if($data["user_id"] > 0){
                $sql = $sql.$psAnd." b.user_id ='".$data["user_id"]."'";;
                $psAnd = " and ";
            }
    
            $result=BindAccount::findBySql($sql)->asArray()->all();
            return Output::Code(200, $result, "success");
        }
    }

    /**
     * 新增修改绑定信息
     */
    public function actionSaveOrUpdateBindAccount()
    {
        $data = $this->get_bindData();
        $model = null;
        if($data["id"] > 0){
            $model = BindAccount::findOne($data["id"]);
        } else {
            $model = new BindAccount;
        }
        if($data["company_id"])
        $model->company_id =  $data["company_id"];
        if($data["user_id"])
        $model->user_id =  $data["user_id"];
        
        if($model->save()>0) {
            return Output::Code(200, $model, "编辑成功"); 
        } else {
            return Output::Code(500, $model, "编辑失败");  
        }
    }


    /**
     * 删除单位绑定
     */
    public function actionDeleteBindAccount()
    {
        $data =$this->getAdminData();
        if($data["username"] !=null && $data["username"] != "" && $data["password"] !=null && $data["password"] != ""){
            $boo = $this->get_user($data["username"],$data["password"]);
            if($boo) {
                if((int)$data["id"] > 0){
                    $count= BindAccount::find()->where(["company_id"=>$data["id"]])->count();
                    if($count > 0) {
                        $model= BindAccount::deleteAll(["company_id" =>$data["id"]]);
                        return Output::Code(200, $model, "删除成功");
                    } else {
                        return Output::Code(500, $data, "要删除的单位绑定信息不存在");
                    }
                }
            } else {
                return Output::Code(500, $data, "用户名或密码错误,不能进行删除");
            }
        }else {
            return Output::Code(500, $recruit, "用户名和密码不能为空");
        }
        
    }




       /**
     * 获取投递信息
     */
    function get_DeliveryData(){
        return $data = [
            "id" => Request::Post("id")?Request::Post("id"):0,
            "resume_id" => Request::Post("resume_id")?Request::Post("resume_id"):0,
            "recruit_id" => Request::Post("resume_id")?Request::Post("resume_id"):0,
            "create_time" => Request::Post("create_time"),
            "status" => Request::Post("status"),
            "user_id" => Request::Post("user_id"),
            "cname" => Request::Post("cname"),
            "jobName" => Request::Post("jobName")
        ];
    }

    /**
     * 查询投递信息
     */
    public function actionDeliveryInfo(){

        $data = $this->get_DeliveryData();
        $pageType = Request::Post("type")?Request::Post("type"):0;

        if($pageType== 0){
            $query = DeliveryInfo::find()->alias("d")
            ->select(["d.*","r.jobName","c.name"])
            ->leftJoin("recruitInfo r","`d`.`recruit_id` = `r`.`id`")
            ->leftJoin("company c", "`c`.`id` = `r`.`company_id`");
            $psAnd = true;
            if($data["cname"] != null){
                $sql = ["like","c.name", $data["cname"]];
                $query->Where($sql);
                $psAnd = false;
            }
            if($data["jobName"] != null){
                $sql = ["like","r.jobName", $data["jobName"]];
                if($psAnd){
                    $query->Where($sql);
                } else{
                    $query->andWhere($sql);
                }
                $psAnd = false;
            }

            if($data["user_id"] > 0){
                $sql = ["d.user_id"=>$data["user_id"]];
                if($psAnd){
                    $query->Where($sql);
                } else{
                    $query->andWhere($sql);
                }
                
                $psAnd = false;
            }
            $pagination = new Pagination([
                'defaultPageSize' => 20,
                'totalCount' => $query->count(),
            ]);
        
            $countries = $query->orderBy("id")
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->asArray()
                ->all();

            $recruit = [
                'countries' => $countries,
                'pagination' => $pagination,
            ];
                return Output::Code(200, $recruit, "success");
        } else {
            $sql = "SELECT d.*,r.jobName,c.name FROM delivery_info d LEFT JOIN recruitInfo r ON d.recruit_id = r.id LEFT JOIN company c ON c.id = r.company_id";
            $psAnd = " Where ";
            if($data["cname"] != null){
                $sql = $sql.$psAnd." c.name like '%".$data["cname"]."%'";
                $psAnd = " and ";
            }
            if($data["jobName"] != null){
                $sql = $sql.$psAnd." r.jobName like '%".$data["jobName"]."%'";
                $psAnd = " and ";
            }

            if($data["user_id"] > 0){
                $sql = $sql.$psAnd." d.user_id ='".$data["user_id"]."'";;
                $psAnd = " and ";
            }
        }
        $result=DeliveryInfo::findBySql($sql)->asArray()->all();
        return Output::Code(200, $result, "success");
    }


  
    /**
     * 新增修改投递信息
     */
    public function actionSaveOrUpdateDeliveryInfo()
    {
        $data = $this->get_DeliveryData();
        $model = null;
        if($data["id"] > 0){
            $model = DeliveryInfo::findOne($data["id"]);
            if($data["status"])
            $model->status =  $data["status"];
        } else {
            $model = new DeliveryInfo;
            if($data["resume_id"])
            $model->resume_id =  $data["resume_id"];
            if($data["recruit_id"])
            $model->recruit_id =  $data["recruit_id"];
            if($data["create_time"])
            $model->create_time = date("y-m-d");
            if($data["user_id"])
            $model->user_id =  $data["user_id"];
            if($data["status"])
            $model->status =  $data["status"];

            $model->sort = DeliveryInfo::find()->count();
        }
        
        if($model->save()>0) {
            return Output::Code(200, $model, "编辑成功"); 
        } else {
            return Output::Code(500, $model, "编辑失败");  
        }
    }


    /**
     * 删除投递信息
     */
    public function actionDeleteDeliveryInfo()
    {
        $data =$this->getAdminData();
        if($data["username"] !=null && $data["username"] != "" && $data["password"] !=null && $data["password"] != ""){
            $boo = $this->get_user($data["username"],$data["password"]);
            if($boo) {
                if((int)$data["id"] > 0){
                    $count= DeliveryInfo::find()->where(["id"=>$data["id"]])->count();
                    if($count > 0) {
                        $model= DeliveryInfo::deleteAll(["id" =>$data["id"]]);
                        return Output::Code(200, $model, "删除成功");
                    } else {
                        return Output::Code(500, $data, "要删除的投递信息不存在");
                    }
                }
            } else {
                return Output::Code(500, $data, "用户名或密码错误,不能进行删除");
            }
        }else {
            return Output::Code(500, $recruit, "用户名和密码不能为空");
        }
        
    }
}
