<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Session;
use yii\filters\VerbFilter;

// use app\models\LoginForm;
use app\models\ContactForm;
use app\models\AdminForm;

$session = Yii::$app->session;
$session->open();
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $msg="";
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new AdminForm();

        if ($model->load(Yii::$app->request->post()) ) {
            $request=Yii::$app->request->post();
            $adminer = AdminForm::find()->where(['username' => $request['AdminForm']['username'],'password'=>md5($request['AdminForm']["password"])])->one();
            if($adminer){                

                Yii::$app->session->set("rooter",$adminer->username);  
                Yii::$app->session->set("rootid",$adminer->id);  
                Yii::$app->session->set("rootanth",$adminer->auth);  
              
                $this->redirect(['index/main']);
            }else{
                 $msg='用户名或密码错误';
            }


            // return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,'msg'=>$msg
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
