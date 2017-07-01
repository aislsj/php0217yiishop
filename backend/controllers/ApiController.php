<?php
namespace backend\controllers;

use backend\models\Brand;
use backend\models\Goods;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Response;

class ApiController extends Controller{
    public $enableCsrfValidation = false;

    public function init()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        parent::init();
    }
    //获取品牌下面的商品
    public function actionGetGoodsByBrand(){
        if($brand_id = \Yii::$app->request->get('brand_id')){
            $good = Goods::find()->where(['brand_id'=>$brand_id])->asArray()->all();
            return ['status'=>'1','msg'=>'','data'=>$good];
        }
        return ['status'=>'-1','msg'=>'参数不正确'];
    }


    //会员注册
    public function actionUserRegister(){

    }
}