<?php

namespace frontend\models;

use frontend\models\Member;
use yii\base\Model;
use yii\db\ActiveRecord;

class LoginForm extends Model
{
    public $username;//用户名
    public $password;//密码
    public $code;
    public $rember_me;


    public function rules()
    {
        return [
            [['username','password'],'required'],
            //添加自定义验证方法
            ['rember_me','safe'],
//            ['rember_me','boolean'],
//            ['ais','boolean'],
            ['code','captcha','message'=>'验证码输入错误']
        ];
    }

    public function attributeLabels()
    {
        return [
            'code'=>'验证码:',
            'username'=>'用户名:',
            'password'=>'密码:',
            'rember_me'=>'记住密码:'
        ];
    }

    public function loginUser(){
//        var_dump($model);exit;
        //根据用户名判断数据库有没有值

        $member = Member::findOne(['username'=>$this->username]);
//        var_dump($member);exit;
        if($member){
            if(\Yii::$app->security->validatePassword($this->password,$member->password_hash)){
                //这里是用户和密码都正常的了,确认登录了的执行方法
                $duration = $this->rember_me?7*24*3600:0;
//                var_dump($member);exit;
                $member->last_login_time=time();
                $member->save();
//                var_dump($member);exit;
//                \Yii::$app->user->login($admin,$duration);
                \Yii::$app->user->login($member,$duration);
                return true;
//                var_dump(\Yii::$app->user->login($member,$duration));exit;
            }else{
                $this->addError('password','密码不正确');
                return false;
            }
        }else{
            $this->addError('username','账号不存在');
            return false;
        }
    }
}