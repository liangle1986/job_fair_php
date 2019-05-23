<?php

namespace app\controllers;

use PHPExcel;
use app\models\Company;
use app\models\RecruitInfo;
use app\models\Street;
use app\components\Request;

use Da\QrCode\QrCode;

use Yii;
class ImportController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionImport(){

        date_default_timezone_set("Asia/Shanghai"); //设置时区
        // $code = $_FILES['file'];//获取小程序传来的excel
        //读取数据$code['tmp_name']
        $si=Request::Get('street_id')?Request::Get('street_id'):0;
        $this->data_import("../../".$si.".xls",$si);
        // if(is_uploaded_file($code['tmp_name'])) {  
        //     //把文件转存到你希望的目录（不要使用copy函数）  
        //     $uploaded_file=$code['tmp_name'];  
        //     $username = "min_excel";
        //     //我们给每个用户动态的创建一个文件夹  
        //     $user_path=$_SERVER['DOCUMENT_ROOT']."/m_php/".$username;  
        //     //判断该用户文件夹是否已经有这个文件夹  
        //     if(!file_exists($user_path)) {  
        //         //mkdir($user_path); 
        //         mkdir($user_path,0777,true); 
        //     }  
         
        //     //$move_to_file=$user_path."/".$_FILES['file']['name'];  
        //     $file_true_name=$code['name'];  
        //     $move_to_file=$user_path."/".time().rand(1,1000)."-".date("Y-m-d").substr($file_true_name,strrpos($file_true_name,"."));//strrops($file_true,".")查找“.”在字符串中最后一次出现的位置  
        //     //echo "$uploaded_file   $move_to_file";  
        //     //上传成功后获取excel的数据
        //     if(move_uploaded_file($uploaded_file,iconv("utf-8","gb2312",$move_to_file))) {  
        //         //路径打印出来看看
        //         return $move_to_file;
        //         $this->data_import($move_to_file);
        //     } else {  
        //         return Output::code(500, '', "上传失败");
         
        //     }  
        // } else {  
        //     return Output::code(500, '', "上传失败");
        // } 
    
    }
    public function data_import($file,$si)
    {

        //拿到街道数组
        $street=Street::find()->all();




        require(Yii::getAlias("@vendor")."/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php");//引入读取excel的类文件
        require(Yii::getAlias("@vendor")."/phpoffice/phpexcel/Classes/PHPExcel.php");
        $filename=$file;//print_r($filename);exit;
        $fileType=\PHPExcel_IOFactory::identify($filename);//自动获取文件的类型提供给phpexcel用
        $objReader=\PHPExcel_IOFactory::createReader($fileType);//获取文件读取操作对象
        $excel = $objReader->load($filename);//引入文件
        $excelSheets = $excel->getAllSheets();
        
        
        //循环所以页
        foreach ($excelSheets as $SheetIndex => $activeSheet) {
            
            //获取当前页的总行数
            $allRow = $activeSheet->getHighestRow();//总行数
           
            //获取总列数
            $allColumn = $activeSheet->getHighestColumn();//取得最大的列号
            //将列数转换为数字 列数大于Z的必须转  A->1  AA->27
            $allcount = \PHPExcel_Cell::columnIndexFromString($allColumn);
    
            //获取合并的单元格
           $columnCells = $activeSheet->getMergeCells();
           $arrlist = $this->get_indexArray($columnCells);
            // foreach($columnCells as $keys=>$v)
            // {
            //     echo $v;
            // }
            // for($i=1;$i<=count($columnCells); $i++){
            //     echo $columnCells[$i];
            // }
          
           
            //单位数组
            $comArr= [];
             //岗位集合
            $tmpend = [];
            $cid=0;
            //短时存储信息
            //从第二行开始读 第一行为标题
            for ($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
                $tmpbegin=[];
                //从第A列开始输出
                for ($currentColumn = 0; $currentColumn < $allcount; $currentColumn++) {

                    // echo json_encode($arrlist[$currentColumn]);
                    //获取合并单元格开始和结束
                    // $index = $this->get_pos($columnCells[$currentColumn + 1]);
         
                    
                   //获取当前列的列头
                   $coltotal = $this->IntToChr($currentColumn);
                        // $map = $this->get_toMap($arrlist,$currentColumn,$currentRow);
                        // echo json_encode($map);
                        $min = "";
                        $max =0;
                        $showmax =100000;
                        for($inds=0; $inds< count($arrlist); $inds++){
                            if($coltotal == $arrlist[$inds][0]){
                                if($showmax > $arrlist[$inds][1] && $arrlist[$inds][1] >= $currentRow) {
                                    $min = $arrlist[$inds][0];
                                    $max = $arrlist[$inds][1];
                                    $showmax = $max;
                                }
                                }
                               
                            }
               
                    $val = $activeSheet->getCellByColumnAndRow($currentColumn, $currentRow)->getValue();
                    
                   
                    // echo $coltotal."=".$min."=".$max."=".$currentRow."\\";
                    if($min == $coltotal){
                        if($max >= $currentRow && (trim($val) == null || trim($val) == "")){
                            $dd = $max-$currentRow+3;
                            $val = $activeSheet->getCellByColumnAndRow($currentColumn, $dd)->getValue();
                           
                        }
                        $tmpbegin[]=trim($val);
                        // $dataArr[$currentRow][$coltotal] = trim($val);
                    } else{
                        $tmpbegin[]=trim($val);
                        // $dataArr[$currentRow][$coltotal] = trim($val);
                    }
                    
                    // echo $val."========".$max."=======".$currentRow;

                 
                   
                    // if($currentRow == (int)$max){
                    //     $comArr["data"][] = $dataArr;
                    // } else {
                    //     //plan 2
                    //     $val = $currentSheet->getCellByColumnAndRow($currentColumn, $currentRow)->getValue();
                    //     if($currentColumn == 1 || $currentColumn == 2 || $currentColumn == 3 || $currentColumn == 4) {
                    //         if(trim($val) != null && trim($val) !="" && $currentColumn != 2) {
                    //             $comArr[$coltotal.$currentRow][] = trim($val);
                    //         }
                    //     } else {
                    //         $dataArr[$currentRow][] = trim($val);
                    //     }
                     
                    // }
                   
                    //如果输出汉字有乱码，则需将输出内容用iconv函数进行编码转换，如下将gb2312编码转为utf-8编码输出 $arr[$currentRow][]=  iconv('utf-8','gb2312', $val)."＼t";

                    //将每列内容读取到数组中
                    // $arr[$currentRow][] = trim($val);

                }
                //数据处理
                if($tmpbegin[4]){
                    if(isset($tmpend[2])){
                        if($tmpend[2]!=$tmpbegin[2]){
                             $count=Company::find()->where(['name'=>$tmpbegin[2]])->count();
                            if($count==0){
                               $cid=$this->addcomp($tmpbegin,$si,$street);
                            }else{
                                $carr=Company::find()->where(['name'=>$tmpbegin[2]])->one();
                                $cid=$carr->id;
                            }
                            
                        }
                    }else{
                        $cid=$this->addcomp($tmpbegin,$si,$street);
                    }
                    


                    $model2=new RecruitInfo;
                    $model2->jobName=$tmpbegin[4];
                    $model2->scene_join_number=$tmpbegin[13];
                    $model2->mansize=$tmpbegin[5];
                    $model2->age=$tmpbegin[6];
                    $model2->record=$tmpbegin[7];
                    $model2->pay=$tmpbegin[8];
                    $model2->workingplace=$tmpbegin[12];
                    $model2->company_id=$cid;
                    $model2->work_content=$tmpbegin[10];
                    $model2->work_demand=$tmpbegin[9];
                    $model2->minpay=(int)$tmpbegin[15];
                    $model2->maxpay=(int)$tmpbegin[16];
                    $model2->save();
                    $tmpend=$tmpbegin;
                }
                

            }
            return "导入完成";
            //打印
            // var_dump($dataArr);
        }
    }
    function addcomp($data,$si,$street){
        $model=new Company;
        $model->name=$data[2];
        $model->address=$data[12];
        $model->remarks=$data[3];
        if($si!=0){
            $model->street_id=$si;
            $model->area=$this->getstreetname($street,$si);
        }else{
            $model->street_id=$this->getstreetid($street,$data[0]);
            $model->area=$this->getstreetname($street,$this->getstreetid($street,$data[0]));
        }

        
        $model->showno=$data[1];
        $model->save();
        return Yii::$app->db->getLastInsertID();
    }
    function getstreetid($street,$val){
        $re=0;
        for($i=0;$i<count($street);$i++){
            if(strstr($val,$street[$i]["tip"])){
                $re=$street[$i]["id"];
            }
        }
        return $re;
    }
    function getstreetname($street,$id){
        $re="";
        for($i=0;$i<count($street);$i++){
            if($id==$street[$i]["id"]){
                $re=$street[$i]["street_name"];
            }
        }
        return $re;
    }
    function get_pos($ar) {
        $col = $row = array();
        foreach($ar as $v) {
            preg_match_all('/([A-Z]+)(\d+):([A-Z]+)(\d+)/', $v, $r);
            $col = array_merge($col, $r[1], $r[3]);
            $row = array_merge($row, $r[2], $r[4]);
        }
        return array(min($col), max($row));
    }

    function get_indexArray($cells){
        $result = array();
        foreach($cells as $keys=>$v)
        {
            array_push($result, $this->get_pos(array($v)));
        }
        return $result;
    }

    /**
     * 数字转字母 （类似于Excel列标）
     * @param Int $index 索引值
     * @param Int $start 字母起始值
     * @return String 返回字母
     */
    function IntToChr($index, $start = 65) {
        $str = '';
        if (floor($index / 26) > 0) {
            $str .= IntToChr(floor($index / 26)-1);
        }
        return $str . chr($index % 26 + $start);
    }

    function get_toMap($arrlist,$column,$rows){
         //获取当前列的列头
         $coltotal = $this->IntToChr($column);
        $result =array();
        $showmax = 100000;
        $min = $arrlist[0][0];
        $max =$arrlist[0][1];
        $showmax =$max;
        for($inds=0; $inds< count($arrlist); $inds++){
            if($coltotal == $arrlist[$inds][0]){
                if($showmax > $arrlist[$inds][1] && $arrlist[$inds][1] >= $rows) {
                    $min = $arrlist[$inds][0];
                    $max = $arrlist[$inds][1];
                    $showmax = $max;
                    if($arrlist[$inds] !=null && $arrlist[$inds] != "")
                    $result = $arrlist[$inds];
                        
                }
                }
               
            }
        return ($result);
    }


    public function actionQrcodefn()
    {
        $res=Company::find()->all();

        foreach ($res as $key) {
            $json=json_encode(Array('company_id'=>$key['id']));
            $qrCode = (new QrCode($json))
            ->setSize(500)
            ->setMargin(150)->setLabel($key["name"])
            ->useForegroundColor(0, 0, 0);
                // $qrCode->writeFile(__DIR__ . '/code.jpg'); // writer defaults to PNG when none is specified
                header('Content-Type: '.$qrCode->getContentType());
            $file=$qrCode->writeFile( '../web/code/'.$key["name"].'.jpg');
        }


        
            
         
    

    }


}
