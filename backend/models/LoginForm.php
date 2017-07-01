<?php
namespace backend\models;

use backend\models\User;
use yii\base\Model;
use yii\db\ActiveRecord;

class LoginForm extends Model{
    public $username;//用户名
    public $password;//密码
    public $code;
    public $ais;

    public function rules()
    {
        return [
            [['username','password'],'required'],
            //添加自定义验证方法

            ['ais','boolean'],
            ['code','captcha','captchaAction'=>'user/captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'code'=>'请输入验证码',
            'username'=>'用户名',
            'password'=>'密码',
            'ais'=>'记住密码'
        ];
    }


    public function login(){
        //1 根据用户名查找用户
        $admin = User::findOne(['username'=>$this->username]);
        if($admin){
            //2 验证密码
            if(\Yii::$app->security->validatePassword($this->password,$admin->password_hash)){
                //3 登录
                //自动登录
                $duration = $this->ais?7*24*3600:0;
//                var_dump($admin);exit;
                \Yii::$app->user->login($admin,$duration);
                return true;
            }else{
                $this->addError('password','密码不正确');
            }
        }else{
            $this->addError('username','用户名不存在');
        }
        return false;
    }

}