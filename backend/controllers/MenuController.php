<?php

namespace backend\controllers;

use backend\models\Menu;

class MenuController extends BackendController
{

//    菜单列表添加
    public function actionAdd(){
        $model = new Menu();
        if($model->load(\Yii::$app->request->post())&& $model->validate()){
            \Yii::$app->session->setFlash('success','添加成功');
            if($model->parent_id==''){
                $model->parent_id=0;
            }
            $model->save();
            return $this->redirect('index');
        }
        return $this->render('add',['model'=>$model]);
    }

//    菜单列表显示
    public function actionIndex(){
        $model = Menu::find()->all();
        return $this->render('index',['models'=>$model]);
    }

    public function actionDelete($id){
       $model = Menu::findOne($id);
        $top = $model->parent_id;
        if($top==0){
            \Yii::$app->session->setFlash('success','该分类为顶级分类,无法删除');
            return $this->redirect('index');
        }else{
            $model->delete();
            return $this->redirect('index');
        }
    }

    public function actionEdit($id){
        $model = Menu::findOne($id);
        if($model->load(\Yii::$app->request->post())&& $model->validate()){
            if($model->parent_id==''){
                $model->parent_id=0;
            }
            $model->save();
            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect('index');
        }
        return $this->render('add',['model'=>$model]);
    }
}
