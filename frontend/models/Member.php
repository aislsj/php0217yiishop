<?php

namespace frontend\models;

use Yii;

use yii\web\IdentityInterface;

/**
 * This is the model class for table "member".
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $email
 * @property string $tel
 * @property integer $last_login_time
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Member extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public $code;
    public $password;
    public $tel_code;
    public $rember_me;
    public $password_two;
//    public $username;//用户名


    public static function tableName()
    {
        return 'member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'required','message'=>'用户名不能为空'],
//            ['username','unique','message'=>'用户名已被注册'],
//            [['password','password_two'], 'required','message'=>'密码不能为空'],
            ['password', 'required','message'=>'密码不能为空'],
            [['last_login_time', 'status', 'created_at', 'updated_at'], 'integer'],
            [['password_hash'], 'string', 'max' => 150],
//            [['tel'], 'match', 'pattern'=>'/^1[34578]{1}\d{9}$/','message'=>'电话格式不正确'],
//            [['email'],'match','pattern'=>'^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$^','message'=>'邮箱格式不正确'],
//            ['code','captcha','message'=>'验证码输入错误'],
            ['code','safe'],
            ['tel_code','validateSms'],
            [['auth_key'], 'string', 'max' => 32],
            ['rember_me','safe'],
//            ['password_two','compare','compareAttribute'=>'password','message'=>'两次密码输入不一致']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID: ',
            'username' => '用户名:',
            'password'=>'密码:',
            'password_two'=>'确认密码:',
            'code'=>'验证码:',
            'tel_code'=>'验证码:',
            'password_hash' => '密码:',
            'email' => '邮箱:',
            'tel' => '电话:',
            'last_login_time' => '最后登录时间',
            'status' => '状态(1正常，0删除)',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
            'rember_me'=>'自动登录',
            'auth_key' => 'Auth Key',
        ];
    }
    public function actions() {
        return [
            'captcha' =>  [
                'class' => 'yii\captcha\CaptchaAction',
                'height' => 50,
                'width' => 80,
                'minLength' => 4,
                'maxLength' => 4
            ],
        ];
    }
    public function addUser($model){
        $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password);
        $model->status=1;
        $model->created_at = time();
        $model->save(false);
        return true;
    }

    //验证短信验证码
    public function validateSms()
    {
        //缓存里面没有该电话号码
        $value = Yii::$app->cache->get('tel_'.$this->tel);
        if(!$value || $this->tel_code != $value){
            $this->addError('tel_code','验证码不正确');
        }
    }









    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        //通过id获得账号
        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        //获取当前账号的id
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() == $authKey;
    }

    //用户登录
    public function loginUser(){
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
                \Yii::$app->user->login($member,$duration);
                return true;
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
