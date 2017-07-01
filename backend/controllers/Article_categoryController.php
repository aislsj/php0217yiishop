<?php

namespace backend\controllers;

use backend\models\ArticleCategory;
use yii\db\ActiveRecord;

class Article_categoryController extends BackendController
{
    //显示文章列表
    public function actionIndex()
    {
        $model = ArticleCategory::find()->all();
        return $this->render('index',['models'=>$model]);
    }
    //添加文章
    public function actionAdd(){
        $model = new ArticleCategory();
        if($model->load(\yii::$app->request->post())){
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','文章添加成功');
                return $this->redirect(['article_category/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //修改文章
    public function actionEdit($id){
        $model = ArticleCategory::findOne(['id'=>$id]);
        if($model->load(\yii::$app->request->post())){
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','文章修改成功');
                return $this->redirect(['article_category/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //删除文章
    public function actionDelete($id){
        $models = ArticleCategory::findOne(['id'=>$id]);
        $models->status=-1;
        $models->save();
        return $this->redirect(['article_category/index']);
    }
}
