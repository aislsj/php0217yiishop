<?php

namespace frontend\controllers;

use backend\models\GoodsCategory;
use frontend\models\Address;
use frontend\models\Member;

class AddressController extends \yii\web\Controller
{
    public $layout = 'address';

    //收货地址和收货地址添加列表
    public function actionIndex()
    {
        $category = GoodsCategory::findAll(['parent_id'=>0]);//分类
        $model = new Address();
        $member = \Yii::$app->user->identity;
        if($model->load(\Yii::$app->request->post())&& $model->validate()){
//            var_dump($model);exit;
            $model->member_id = $member->id;
            if($model->Save_address($model)){
                return $this->redirect('index.html');
            }
        }
        $model2  = address::findAll(['member_id'=>$member->id]);
        return $this->render('index',['model'=>$model,'models2'=>$model2,'categories'=>$category]);
    }
    //收货地址删除
    public function actionDelete($id){
        $model = Address::findOne(['id'=>$id]);
        $model->delete();
        return $this->redirect('index.html');
    }
    //收货地址修改
    public function actionEdit($id){
        $category = GoodsCategory::findAll(['parent_id'=>0]);//分类
        $model = Address::findOne(['id'=>$id]);
        $member = \Yii::$app->user->identity;
        if($model->load(\Yii::$app->request->post())&& $model->validate()){
            $model->member_id = $member->id;
            if($model->Save_address($model)){
                return $this->redirect('index.html');
            }
        }
        $model2  = address::findAll(['member_id'=>$member->id]);
        return $this->render('index',['model'=>$model,'models2'=>$model2,'categories'=>$category]);
    }

        //修改默认地址
    public function actionIsdefault($member_id,$id){
        $model_self = Address::findOne(['id'=>$id]);//选中的地址
    if($model_self->is_default==0){//判断是否为默认地址,是就跳过
        $model_all = Address::findAll(['member_id'=>$member_id]);//所有的地址
        foreach($model_all as $all){
            $all->is_default = 0;
            $all->save();
        }
        $model_self->is_default=1;
        $model_self->save();
    }
        return $this->redirect('index.html');
    }
}
