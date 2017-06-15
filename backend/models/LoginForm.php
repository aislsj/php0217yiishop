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
            ['username','validateName'],
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

//$model->password_hash=\Yii::$app->security->generatePasswordHash($model->password_hash);

    //自定义验证方法
    public function validateName(){
        $account = User::findOne(['username'=>$this->username]);
        if($account){
            //用户存在 验证密码
            if(!\Yii::$app->security->validatePassword($this->password,$account->password_hash)){
                $this->addError('password','密码不正确');
            }else{
                //账号秘密正确，登录
                \Yii::$app->user->login($account);
            }
        }else{
            //账号不存在  添加错误
            $this->addError('username','账号不正确');
        }
    }
}