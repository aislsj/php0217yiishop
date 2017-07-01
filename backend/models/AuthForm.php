<?php
namespace backend\models;

use yii\base\Model;
use yii\rbac\Permission;

class AuthForm extends Model{

    public $name;//权限名称
    public $details;//权限内容


    public function rules(){
        return[
            [['name','details'],'required'],
//            ['name','valdate'],//因为组件不能重名,所以要验证,这里是自定义验证

        ];
    }
    public function attributeLabels(){
        return[
          'name'=>'权限名称',
            'details'=>'权限描述',
        ];
    }


    public function addAuth(){
        //实例化组件
        $authManager = \Yii::$app->authManager;
        //判断用户名是否存在
        if($authManager->getPermission($this->name)){
            $this->addError('name','权限已存在');
        }else{
            //创建权限(名称和描述)
            $permission = $authManager->createPermission($this->name);
            //description不能更改
            $permission->description = $this->details;
//       var_dump($permission->description);exit;
            return $authManager->add($permission);//保存并返回 add返回布尔
        }
        return false;
    }

//    public function valdate(){
//        $authManger =\Yii::$app->authManager;
//        //获得权限
//        if($authManger->getPermission($this->name)){
//            $this->addError('name','权限已存在');
//        }
//    }

    //从权限中加载数据
    public function loadData(Permission $permission){
        $this->name = $permission->name; //名称赋值
        $this->details = $permission->description;//内容赋值

    }

    public function updateRules($name){
        $authManager = \Yii::$app->authManager;
        $permiggion = $authManager->getPermission($name);
        if($name != $this->name && $authManager->getPermission($this->name)){
            $this->addError('name','权限已存在');
        }else{
            //赋值
            $permiggion->name = $this->name;
            $permiggion->description = $this->details;
            //更新权限
            return $authManager->update($name,$permiggion);//update返回布尔值
        }
        return false;
    }
}