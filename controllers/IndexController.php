<?php

namespace app\controllers;

class IndexController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionMain(){
    	return $this->render('index');
    }
}
