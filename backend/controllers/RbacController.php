<?php

namespace backend\controllers;

use backend\models\AuthForm;
use backend\models\RoleForm;
use yii\web\NotFoundHttpException;
use backend\models\PermissionForm;


class RbacController extends BackendController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    //添加权限
    public function actionRulesAdd(){
        $model = new AuthForm();
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            if($model->addAuth()){
                \Yii::$app->session->setFlash('success','权限添加成功');
                return $this->redirect(['rules-index']);
            }
        }
        return $this->render('rules-add',['model'=>$model]);
    }

    //权限列表
    public function actionRulesIndex(){
        $models = \Yii::$app->authManager->getPermissions();
        return $this->render('rules-index',['models'=>$models]);
    }

    //修改权限
    public function actionEditRules($name){
        //获得传过来的数据
        $permission = \Yii::$app->authManager->getPermission($name);
//        var_dump($permission);exit;
        if($permission == null){
            throw new NotFoundHttpException('权限不存在');
        }
        //实例化表单模型
        $model = new AuthForm();
        //调用模型方法,接收参数
        $model->loadData($permission);
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            if($model->updateRules($name)){
                \Yii::$app->session->setFlash('success','权限修改成功');
                return $this->redirect(['rules-index']);
            }
        }
        return $this->render('rules-add',['model'=>$model]);
    }

    //删除权限
    public function actionDeleteRules($name){
        $permission = \Yii::$app->authManager->getPermission($name);
        if($permission==null){
            throw new NotFoundHttpException('权限不存在');
        }
        \Yii::$app->authManager->remove($permission);
        \Yii::$app->session->setFlash('success','权限删除成功');
        return $this->redirect(['rules-index']);
    }

    //添加角色
    public function actionRoleAdd(){
        $model = new RoleForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->addRole()){
                \Yii::$app->session->setFlash('success','角色添加成功');
                return $this->redirect(['role-index']);
            }
        }
        return $this->render('role-add',['model'=>$model]);
    }
    //角色列表
    public function actionRoleIndex(){
        $models = \Yii::$app->  authManager->getRoles();
        return $this->render('role-index',['model'=>$models]);
    }
    //角色修改
    public function actionEditRole($name){

        $role = \Yii::$app->authManager->getRole($name);
        if($role==null){
            throw new NotFoundHttpException('角色不存在');
        }
//      var_dump($role);exit;
        $model = new RoleForm();
        $model->loadData($role);
//       var_dump($model->loadData($role));exit;
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->updateRole($name)){
                \Yii::$app->session->setFlash('success','角色修改成功');
                return $this->redirect(['role-index']);
            }
        }
        return $this->render('role-add',['model'=>$model]);
    }
    //角色删除rbac/del-role'
    public function actionDelRole($name){
//        echo 11111111111;exit;
        $role = \Yii::$app->authManager->getRole($name);
        if($role==null){
            throw new NotFoundHttpException('角色不存在');
        };
//        var_dump($role);exit;
        \Yii::$app->authManager->remove($role);
        \Yii::$app->session->setFlash('success','角色删除成功');
        return $this->redirect(['role-index']);
    }
}
