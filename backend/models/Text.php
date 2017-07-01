<?php

namespace backend\models;

use yii\base\Model;

class Text extends Model{
    public $code;
    public $name;
    public function rules()
    {
        return [
            ['username', 'required'],
            ['code','captcha','captchaAction'=>'text/captcha','message'=>'验证码输入错误'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'name' => '用户名:',
            'code' => '验证码:',
        ];
    }
}