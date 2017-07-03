<?php

namespace frontend\controllers;

use backend\models\GoodsCategory;


class IndexController extends \yii\web\Controller{

    public $layout = 'index';
    public function actionIndex(){
        //查找商品详细分类的首分类并添加到主视图上
        $category = GoodsCategory::findAll(['parent_id'=>0]);
        return $this->render('index',['categories'=>$category]);
    }
}