<?php

namespace frontend\controllers;

use frontend\models\LoginForm;
use frontend\models\Member;
use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Flc\Alidayu\Requests\IRequest;


class MemberController extends \yii\web\Controller
{
    public $layout = 'login';
    //用户注册
    public function actionRegister()
    {
        $model = new Member();
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            if($model->addUser($model)){
                \Yii::$app->session->setFlash('success','注册成功');
                return $this->redirect('index');
            }else{
                var_dump($model->getErrors());
                exit;
            }
        }
        return $this->render('register',['model'=>$model]);
    }
    //用户登录
    public function actionLogin(){
        $model = new Member();
//        $model = new LoginForm();
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            if($model->loginUser()){
                \Yii::$app->session->getFlash('success','登录成功');
//                var_dump(\Yii::$app->user->identity);exit;
                return $this->redirect('check.html');
            }else{
                var_dump($model->loginUser());exit;
            }
        }
        return $this->render('login',['model'=>$model]);
    }

    //用户退出
    public function actionLogout(){
        \Yii::$app->user->logout();
    }

    //判断用户登录成功没有
    public function actionCheck(){
        var_dump(\Yii::$app->user->identity);
    }

    public function actionIndex()
    {
        echo 'ok';exit;
        return $this->render('index');
    }

    //测试电话
    public function actionChecktel(){
        // 配置信息
        $config = [
            'app_key'    => '24478575',//阿里大于中自己申请的参数
            'app_secret' => '76a31b5a1d588ca9a3903c489e5be00d',//阿里大于中自己申请的参数
            // 'sandbox'    => true,  // 是否为沙箱环境，默认false
        ];
        // 使用方法一
        $client = new Client(new App($config));
        $req    = new AlibabaAliqinFcSmsNumSend;
        $req->setRecNum('18328494698')//用户的电话号码
            ->setSmsParam([
                'code' => rand(1000, 9999)//发送的验证码\code为自己模板的内容
            ])
            ->setSmsFreeSignName('李双杰的网站')//设置的短信签名
            ->setSmsTemplateCode('SMS_71480187');//设置的短信模板ID
        $code = $client->execute($req);
        var_dump($code);exit;
    }


    //发送电话并且验证
    public function actionSendSms()
    {
        // 配置信息
        $config = [
            'app_key'    => '24478575',//阿里大于中自己申请的参数
            'app_secret' => '76a31b5a1d588ca9a3903c489e5be00d',//阿里大于中自己申请的参数
            // 'sandbox'    => true,  // 是否为沙箱环境，默认false
        ];
        //确保上一次发送短信间隔超过1分钟
        $tel = \Yii::$app->request->post('tel');
        //用正则判断电话号码
        if(!preg_match('/^1[34578]\d{9}$/',$tel)){
            echo '电话号码不正确';
            exit;
        }
        $code = rand(1000,9999);//将要发送的随机码
        // 使用方法一
        $client = new Client(new App($config));
        $req    = new AlibabaAliqinFcSmsNumSend;
        $req->setRecNum($tel)//用户的电话号码
        ->setSmsParam([
            'code' => $code//发送的验证码\code为自己模板的内容
        ])
            ->setSmsFreeSignName('李双杰的网站')//设置的短信签名
            ->setSmsTemplateCode('SMS_71480187');//设置的短信模板ID
        $result = $client->execute($req);//发送到阿里云
//        $result = 1;
        if($result){
            //保存当前验证码 session  mysql  redis  不能保存到cookie
            \Yii::$app->cache->set('tel_'.$tel,$code,5*60);
//            echo 'success'.$code;//在控制台查看验证码
        }else{
            echo '发送失败';
        }
    }
}
