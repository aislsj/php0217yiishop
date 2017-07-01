<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class GoodscategoryController extends BackendController
{
    public function actionIndex()
    {
        $models = GoodsCategory::find()->orderBy('tree,lft')->all();
//        var_dump($models);exit;
        return $this->render('index',['models'=>$models]);
    }

    public function actionText(){
//       $ais =  new GoodsCategory();
//        $ais->name='家用电器';
//        $ais->parent_id = 0;
//        $ais->makeRoot();
//        var_dump($ais);
        //创建二级分类
//        $parent = GoodsCategory::findOne(['id'=>1]);
//        $xjd = new GoodsCategory();
//        $xjd->name='小家电';
//        $xjd->parent_id = $parent->id;
//        $xjd->prependTo($parent);
//        echo '操作成功';
        //获取所有一级分类
//        $roots = GoodsCategory::find()->roots()->all();
//        var_dump($roots);
        // 获取该分类下面的所有子孙分类
        $roots = GoodsCategory::findOne(['id'=>1]);
        $children = $roots->leaves()->all();
        var_dump($children);
    }

    public function actionZtree(){
        $categories = GoodsCategory::find()->asArray()->all();
        return $this->renderPartial('Ztree',['categories'=>$categories]);//不加载布局文件
    }

    public function actionAdd(){
        $model = new GoodsCategory();
        if($model->load(\Yii::$app->request->post())&& $model->validate()){
//            var_dump($model);exit;
            //判断是否是添加一级分类(parent_id是否为0)
            if($model->parent_id){
                //添加非一级分类
                $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);
//                $xjd = new GoodsCategory();
//                $xjd->name='小家电';
//                $xjd->parent_id = $parent->id;
//                $xjd->prependTo($parent);
                  $model->prependTo($parent);//添加到上一级分类下面
            }else{
                //添加一级分类
                $model->makeRoot();
            }
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['goodscategory/index']);
        }
        $categories = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());//框架的二维遍历语法
        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }


    public function actionEdit($id){
        $model =GoodsCategory::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('分类不存在');
        }
        if($model->load(\Yii::$app->request->post())&& $model->validate()){
            //判断是否是添加一级分类(parent_id是否为0)
            if($model->parent_id){
                //添加非一级分类
                $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);
                $model->prependTo($parent);//添加到上一级分类下面
            }else{
                //添加一级分类
                $model->makeRoot();
            }
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['goodscategory/index']);
        }
        $categories = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());//框架的二维遍历语法
        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }
}
