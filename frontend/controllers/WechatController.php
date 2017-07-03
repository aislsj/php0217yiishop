<?php

namespace frontend\controllers;

use yii\web\Controller;

class WechatController extends Controller{
    //安装开启微信的插件eachWechat
    //关闭csrf验证
    public $enableCsrfValidation = false;

    //构造一个url用于微信开发
    public function actionIndex(){

    }
}