<?php

namespace frontend\controllers;

use backend\components\SphinxClient;
use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use yii\base\Controller;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class ListController extends \yii\web\Controller{

    public $layout = 'index';

    public function actionIndex($cate_id)
    {
        $goodsCategory = GoodsCategory::find()->where(['parent_id' => 0])->all();
        $goods = Goods::find()->where(['goods_category_id' => $cate_id])->all();
        $brand = Brand::find()->all();
        return $this->render('index', ['categories' => $goodsCategory, 'goods' => $goods,'brands'=>$brand]);
    }

    //模糊搜索
//    public function actionSerarch(){
//        $brand = Brand::find()->all();
//        $goodsCategory = GoodsCategory::find()->where(['parent_id' => 0])->all();
//        $serarch = \Yii::$app->request->post()['serarch'];
//        //$query = Goods::find()->where(['like','name','耳机']);
//        $goods = Goods::find()->where(['like','name',$serarch])->all();
//        return $this->render('index', ['categories' => $goodsCategory, 'goods' => $goods,'brands'=>$brand]);
//    }

//        全文分词搜索
        public function actionSerarch(){
        $brand = Brand::find()->all();//品牌
        $goodsCategory = GoodsCategory::find()->where(['parent_id' => 0])->all();//商品分类
        $serarch = \Yii::$app->request->post()['serarch'];//搜索条件
            $cl = new SphinxClient();
            $cl->SetServer ( '127.0.0.1', 9312);
//$cl->SetServer ( '10.6.0.6', 9312);
//$cl->SetServer ( '10.6.0.22', 9312);
//$cl->SetServer ( '10.8.8.2', 9312);
            $cl->SetConnectTimeout ( 10 );
            $cl->SetArrayResult ( true );
// $cl->SetMatchMode ( SPH_MATCH_ANY);
            $cl->SetMatchMode ( SPH_MATCH_ALL);
            $cl->SetLimits(0, 1000);
//            $info = '缇米';//需要搜索的词
            $res = $cl->Query($serarch, 'goods');//shopstore_search
//print_r($cl);
            if($res == false){
                throw new NotFoundHttpException('该商品没有找到');
            }else{
                //获取商品id
                $ids = ArrayHelper::map($res['matches'],'id','id');
            }
        //构造sql
            $query = Goods::find();
            $query->where(['in','id',$ids]);
            $pager = new Pagination([
            'totalCount'=>$query->count(),
            'pageSize'=>10
        ]);
        $models = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index', ['categories' => $goodsCategory, 'goods' => $models,'brands'=>$brand]);
    }



    public function actionText(){
        $cl = new SphinxClient();
        $cl->SetServer ( '127.0.0.1', 9312);
//$cl->SetServer ( '10.6.0.6', 9312);
//$cl->SetServer ( '10.6.0.22', 9312);
//$cl->SetServer ( '10.8.8.2', 9312);
        $cl->SetConnectTimeout ( 10 );
        $cl->SetArrayResult ( true );
// $cl->SetMatchMode ( SPH_MATCH_ANY);
        $cl->SetMatchMode ( SPH_MATCH_ALL);
        $cl->SetLimits(0, 1000);
        $info = '缇米';//需要搜索的词
        $res = $cl->Query($info, 'goods');//shopstore_search
//print_r($cl);
        var_dump($res);
    }
}