<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    //显示品牌列表
    public function actionIndex()
    {
        $model = Brand::find()->all();
        return $this->render('index',['models'=>$model]);
    }
    //添加品牌
    public function actionAdd(){
    $model = new Brand();
    if($model->load(\yii::$app->request->post())){
        $model->imgFile=UploadedFile::getInstance($model,'imgFile');
        if($model->validate()){
            if($model->imgFile){
                $filename = '/images/brand/'.uniqid().'.'.$model->imgFile->extension;
                $model->imgFile->saveAs(\Yii::getAlias('@webroot').$filename,false);
                $model->logo = $filename;
            }
            $model->save();
            \Yii::$app->session->setFlash('success','品牌添加成功');
            return $this->redirect(['brand/index']);
        }
    }
    return $this->render('add',['model'=>$model]);
}
    //修改品牌
    public function actionEdit($id){
        $model = Brand::findOne(['id'=>$id]);
        if($model->load(\yii::$app->request->post())){
            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
            if($model->validate()){
                if($model->imgFile){
                    $filename = '/images/brand/'.uniqid().'.'.$model->imgFile->extension;
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$filename,false);
                    $model->logo = $filename;
                }
                $model->save();
                \Yii::$app->session->setFlash('success','品牌修改成功');
                return $this->redirect(['brand/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //删除品牌
    public function actionDelete($id){
        $models = brand::findOne(['id'=>$id]);
        $models->status=-1;
        $models->save();
        return $this->redirect(['brand/index']);
    }
}
