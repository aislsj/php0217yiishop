<?php
namespace backend\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\rbac\Role;


class RoleForm extends Model{
    public $name;
    public $description;
    public $permissions=[];//角色的权限

    public function rules(){
        return[
          [['name','description'],'required'],
            ['permissions','safe']//self表示该属性不需要验证
        ];
    }

    public function attributeLabels(){
            return[
                'name'=>'名称',
                'description'=>'描述',
                'permissions'=>'权限'
            ];
    }

    public static function getPermissionOptions(){
        $authManager = \Yii::$app->authManager;
        return ArrayHelper::map($authManager->getPermissions(),'name','description');
    }



    public function addRole(){

        $authManager = \Yii::$app->authManager;
//        var_dump($authManager->getRole($this->name));exit;
        if($authManager->getRole($this->name)){
            $this->addError('name','角色名已存在');
        }else{
            $role = $authManager->createRole($this->name);
            $role->description = $this->description;//内容赋值

            /*
             * 步骤 ,视图中在checkboxList(\backend\models\RoleForm::getPermissionOptions())调用这里的方法
             * 返回了一个数组return ArrayHelper::map($authManager->getPermissions(),'name','description');
             * 这里添加成功后开始遍历返回来的数组
             * 刚开始的时候定义了一个新数组,这里讲遍历的有值的数据加入空数组
             * if($permission) 如果有这,这里便关联权限$authManager->addChild($role,$permission);
             */

            if($authManager->add($role)){//保存到数据表成功后
                foreach($this->permissions as $permission){
                    $permission = $authManager->getPermission($permission);//获得权限
//                    var_dump($permission);exit;
                    //这里加if是害怕如果在操作的时候有人把权限删了 ,可以加个判断
                    if($permission) $authManager->addChild($role,$permission);//关联权限 addChild(角色,权限,规则)
                }
//                var_dump($role);exit;
                return true;
            }
        }
        return false;
    }

    //更新角色
    public function updateRole($name)
    {
        $authManager = \Yii::$app->authManager;
        $role = $authManager->getRole($name);
        //给角色赋值
        $role->name = $this->name;
        $role->description = $this->description;
        //如果角色名被修改，检查修改后的名称是否已存在
        if($name != $this->name && $authManager->getRole($this->name)){
            $this->addError('name','角色名称已存在');
        }else{
            if($authManager->update($name,$role)){
                //去掉所有与该角色关联的权限
                $authManager->removeChildren($role);
                //关联该角色的权限
                foreach ($this->permissions as $permissionName){
                    $permission = $authManager->getPermission($permissionName);
                    if($permission) $authManager->addChild($role,$permission);
                }
                return true;
            }
        }
        return false;
    }



    public function loadData(Role $role)
    {
//        var_dump($role);exit;
        $this->name = $role->name;//把传过来的值赋值给$this
        $this->description = $role->description;//把传过来的值赋值给$this
        //权限属性赋值
        //获取该角色对应的权限
        $permissions = \Yii::$app->authManager->getPermissionsByRole($role->name);
//        var_dump($permissions);exit;
//        $this->permissions = ['brand/edit','brand/index'];
        foreach ($permissions as $permission){
            $this->permissions[]=$permission->name;
        }
//        var_dump( $this->name);
//        var_dump($this->permissions);exit;
    }
}