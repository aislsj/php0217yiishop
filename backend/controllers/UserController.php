<?php

namespace backend\controllers;

use backend\models\User;
use backend\models\LoginForm;
use yii\web\Request;

use yii\filters\AccessControl;



class UserController extends \yii\web\Controller
{
    //显示管理员列表
    public function actionIndex()
    {
        $model = User::find()->all();
//        var_dump($model);exit;
        return $this->render('index', ['model' => $model]);
    }

    //管理员添加
    public function actionAdd()
    {
        $model = new User();
        if ($model->load(\Yii::$app->request->post())) {

            if ($model->validate()) {
                $model->last_login = time();
                $model->last_ip = $_SERVER["REMOTE_ADDR"];
                $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->save();
                \Yii::$app->session->setFlash('success', '成功');
                return $this->redirect(['user/index']);
            } else {
                var_dump($model->getErrors());
                exit;
            }
        }
        return $this->render('add', ['model' => $model]);
    }

    //管理员修改
    public function actionEdit($id)
    {
        $model = User::findOne($id);
        if ($model->load(\Yii::$app->request->post())) {
            if ($model->validate()){
                $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->save();
                \Yii::$app->session->setFlash('success', '成功');
                return $this->redirect(['user/index']);
            } else {
                var_dump($model->getErrors());
                exit;
            }
        }
        return $this->render('add', ['model' => $model]);
    }

    //删除管理员
    public function actionDelete($id){
        $model = User::findOne($id);
        $model->delete();
        return $this->redirect(['user/index']);
    }


    //登录
    //先认证（对比账号密码），再登录
    public function actionLogin()
    {
        $model = new LoginForm();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            var_dump($model);exit;

            if($model->validate()){
//                var_dump($model->validate());exit;
                $models = User::findOne(['username'=>$model['username']]);
//                var_dump($models);exit;

                $models->last_login = time();
                $models->last_ip = $_SERVER["REMOTE_ADDR"];
//                var_dump($models);exit;
                $models->save(false);
                //跳转到登录检测页
                return $this->redirect(['user/index']);
            }
        }
        return $this->render('login',['model'=>$model]);
    }
//
    //没登录调回到登录页面
    public function actionAis(){
        $model = new LoginForm();
        return $this->render('login',['model'=>$model]);
    }

    //过滤器
    public function behaviors()
    {
        return [
            'acf'=>[
                'class' => AccessControl::className(),//过滤器
               'only'=>['add','edit','delete','index','Ais'],//该过滤器作用的操作 ，默认是所有操作
                'rules'=>[
                    [//未认证用户允许执行view操作
                        'allow'=>true,//是否允许执行
                        'actions'=>['login', 'index','ais'],//指定操作
//                        'roles'=>['?'],//角色？表示未认证用户  @表示已认证用户
                    ],
                    [//已认证用户允许执行add操作
                        'allow'=>true,//是否允许执行
                        'actions'=>['add','edit','delete','index','loginout'],//指定操作
                        'roles'=>['@'],//角色？表示未认证用户  @表示已认证用户
                    ],
                    //其他都禁止执行
                ]
            ],
        ];
    }


    //退出 注销
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        \Yii::$app->session->setFlash('success', '注销成功');
        return $this->redirect(['user/login']);
    }



    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength'=>4,
                'maxLength'=>4,
            ],
        ];
    }
}