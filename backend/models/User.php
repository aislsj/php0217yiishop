<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $last_login
 * @property integer $last_ip
 */

class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public $permissions=[];
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username','password_hash','status'], 'required'],
            [['status', 'created_at', 'updated_at', 'last_login'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['permissions'],'safe'],
            [['password_reset_token'], 'unique'],
            [['email'],'match','pattern'=>'^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$^'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'auth_key' => 'Auth Key',
            'password_hash' => '密码',
            'code'=>'请输入验证码',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => '状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'last_login' => 'Last Login',
            'last_ip' => 'Last Ip',
            'permissions'=>'管理员类型'
        ];
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


    public static function getPermissionOptions(){
        $authManager = \Yii::$app->authManager;

//        return ArrayHelper::map($authManager->getRoles(),'name','description');
        return ArrayHelper::map($authManager->getRoles(),'name','name');

    }


    public function addUser($model){
        $authManager = \Yii::$app->authManager;
            $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password_hash);
        if($model->save()){
            foreach($this->permissions as $role){
                $role = $authManager->getRole($role);//获得权限
                //这里加if是害怕如果在操作的时候有人把权限删了 ,可以加个判断
                if($role) $authManager->assign($role,$model->id);
            }
            return true;
        }
        return false;
    }

    public function updateUser($model){
       $models = User::findOne($model->id);

        $authManager = \Yii::$app->authManager;
         $authManager->getRolesByUser($model->id);
        if($model->password_hash=123456){
            $model->password_hash = $models->password_hash;
        }else{
            $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password_hash);
        }
        if($model->save()){
            $authManager->revokeAll($model->id);
            //关联该角色的权限
            foreach($this->permissions as $role){
                $role = $authManager->getRole($role);//获得权限
                //这里加if是害怕如果在操作的时候有人把权限删了 ,可以加个判断
                if($role) $authManager->assign($role,$model->id);
            }
            return true;
        }
        return false;
    }
}
