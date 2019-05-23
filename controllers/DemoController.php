<?php

namespace app\controllers;
use app\models\Demo;
use app\models\User;
use PHPExcel;
use yii;
class DemoController extends \yii\web\Controller
{
    public function actionIndex()
    {
    	//查
    	$d=Demo::findBySql('SELECT * FROM Demo')->all();
    	if($d){
    		foreach ($d as $key ) {
    			echo $key->id."\n";
    			echo $key->keya."\n";
    		}
    	}else{
    		echo "没有数据";
    	}

    }

    public function actionAdd(){
   
    	$model = new Demo;
		$model->keya = 'aaa';
		$model->keyb  = 'bbb';
		$model->keyc  = 'ccc';
		$model->keyd  = 'ddd';
		if($model->save() > 0){echo "添加成功";echo ",id=".$model->id; }else{echo "添加失败"; } //id自增长，不需要写
    }

    public function actionEdit(){
    	$model = User::findOne(1);//id
		$model->sign = '2';
		$model->save(); 
    }

    public function actionDel(){
    	$count= Demo::model()->deleteAll('username=:name and password=:pass',array(':name'=>'这里填东西',':pass'=>'这里填东西'));
    	if($count>0){echo"删除成功"; }else{echo "删除失败"; } 
    }


    public function actionImport(){
        $this->data_import('test.xls');
    }
    public function data_import($file)
    {
        require(Yii::getAlias("@vendor")."/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php");//引入读取excel的类文件
        require(Yii::getAlias("@vendor")."/phpoffice/phpexcel/Classes/PHPExcel.php");
        $filename=$file;//print_r($filename);exit;
        $fileType=\PHPExcel_IOFactory::identify($filename);//自动获取文件的类型提供给phpexcel用
        $objReader=\PHPExcel_IOFactory::createReader($fileType);//获取文件读取操作对象
        $excel = $objReader->load($filename);//引入文件
        $excelSheets = $excel->getAllSheets();
        foreach ($excelSheets as $SheetIndex => $activeSheet) {
            $sheetColumnTotal = $activeSheet->getHighestRow();//总行数
            if($sheetColumnTotal == 1){
                continue;
            }
            $data=array();
            for($i = 2;$i < $sheetColumnTotal;$i++){

                $data[]=array(
                     $activeSheet->getCell('A'.$i)->getValue(),
                    $activeSheet->getCell('B'.$i)->getValue(),
                    $activeSheet->getCell('C'.$i)->getValue(),
                    $activeSheet->getCell('D'.$i)->getValue(),
                    $activeSheet->getCell('E'.$i)->getValue(),
                    $activeSheet->getCell('F'.$i)->getValue(),
                    $activeSheet->getCell('G'.$i)->getValue(),
                    $activeSheet->getCell('H'.$i)->getValue(),
                    $activeSheet->getCell('I'.$i)->getValue(),
                    $activeSheet->getCell('J'.$i)->getValue(),
                    $activeSheet->getCell('K'.$i)->getValue(),
                    $activeSheet->getCell('L'.$i)->getValue(),
                    $activeSheet->getCell('M'.$i)->getValue(),
                    $activeSheet->getCell('N'.$i)->getValue(),
                    $activeSheet->getCell('O'.$i)->getValue(),
                    $activeSheet->getCell('P'.$i)->getValue(),
                    $activeSheet->getCell('Q'.$i)->getValue(),
                    $activeSheet->getCell('R'.$i)->getValue(),
                    $activeSheet->getCell('S'.$i)->getValue(),
                    $activeSheet->getCell('T'.$i)->getValue(),
                    $activeSheet->getCell('U'.$i)->getValue(),
                    $activeSheet->getCell('V'.$i)->getValue(),
                    $activeSheet->getCell('W'.$i)->getValue(),
                    $activeSheet->getCell('X'.$i)->getValue(),
                    $activeSheet->getCell('Y'.$i)->getValue(),
                    $activeSheet->getCell('Z'.$i)->getValue(),
                    $activeSheet->getCell('AA'.$i)->getValue(),
                    $activeSheet->getCell('AB'.$i)->getValue(),
                    $activeSheet->getCell('AC'.$i)->getValue(),
                    $activeSheet->getCell('AD'.$i)->getValue(),
                    $activeSheet->getCell('AE'.$i)->getValue()

                );
                var_dump($data);
            }
           
        }
    }

}
