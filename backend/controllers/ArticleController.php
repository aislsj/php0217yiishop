<?php

namespace backend\controllers;
use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleDetail;
use yii\web\Request;

class ArticleController extends \yii\web\Controller
{
    //显示文章列表
    public function actionIndex()
    {
        $model = Article::find()->all();
        $model2 = ArticleCategory::find()->all();
//        $model->ais = 1;
//        $model2 = ArticleCategory::find()->all();
        return $this->render('index',['models'=>$model]);
    }


    //添加文章
    public function actionAdd(){
        $model = new Article();
        $model2 = new ArticleDetail();
        $category=ArticleCategory::find()->asArray()->all();
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            $model2->load($request->post());
            if($model->validate()){
                $model->create_time=time();
                $model->save();
                $count=[];
                foreach($category as $v){
                    $count[$v['id']]=$v['name'];
                }
                if($model2->validate()){
                    $model2->article_id=$model->id;
                    $model2->save();
                    \Yii::$app->session->setFlash('success','文章添加成功');
                    return $this->redirect(['article/index']);
                }
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model,'category'=>$category,'model2'=>$model2]);
    }

    //修改文章
    public function actionEdit($id){
        $model = Article::findOne(['id'=>$id]);
        $model2 = ArticleDetail::findOne(['article_id'=>$id]);
        $category=ArticleCategory::find()->asArray()->all();
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            $model2->load($request->post());
            if($model->validate()){
                $model->create_time=time();
                $model->save();
                $count=[];
                foreach($category as $v){
                    $count[$v['id']]=$v['name'];
                }
                if($model2->validate()){
                    $model2->article_id=$model->id;
                    $model2->save();
                    \Yii::$app->session->setFlash('success','文章添加成功');
                    return $this->redirect(['article/index']);
                }
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model,'category'=>$category,'model2'=>$model2]);
    }

    //删除文章
    public function actionDelete($id){
        $models = Article::findOne(['id'=>$id]);
        $models->status=-1;
        $models->save();
        return $this->redirect(['article/index']);
    }

    //查看文章详情
    public function actionShow($id){
        $moldes = Article::findOne(['id'=>$id]);
        $moldes2 =ArticleDetail::findOne(['article_id'=>$id]);
        return $this->render('index_show',['model'=>$moldes,'model2'=>$moldes2]);
    }
}
