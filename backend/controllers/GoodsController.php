<?php

namespace backend\controllers;

use backend\models\Brand;
use backend\models\Goods;
use backend\models\goodscategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsIntro;
use backend\models\GoodsSearchForm;
use yii\data\Pagination;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;


class GoodsController extends \yii\web\Controller
{
    //商品的显示
    public function actionIndex()
    {
        $model = new GoodsSearchForm();
        $query = Goods::find();
        //接收表单提交的查询参数
        $model->search($query);

        //商品名称含有"耳机"的  name like "%耳机%"
        //$query = Goods::find()->where(['like','name','耳机']);
//        $pager = new Pagination([
//            'totalCount'=>$query->count(),
//            'pageSize'=>5
//        ]);
//        $models = $query->limit($pager->limit)->offset($pager->offset)->all();
//        return $this->render('index',['models'=>$models,'pager'=>$pager,'model'=>$model]);
        $models = $query->all();
        return $this->render('index',['models'=>$models,'model'=>$model]);

    }

    //商品的添加
    public function actionAdd(){
        $model = new Goods();
        $model3 = new GoodsIntro();
        $model2 = new GoodsDayCount();
        $model->imgFile=UploadedFile::getInstance($model,'imgFile');
        $category=Brand::find()->asArray()->all();
        $count=[];
        foreach($category as $v){
            if($v['status']>=0){
                $count[$v['id']]=$v['name'];
            }
        }
        if($model->load(\yii::$app->request->post())&& $model3->load(\yii::$app->request->post())){
            if($model->validate()) {
                if ($model->imgFile) {
                    $filename = '/images/goods/' . uniqid() . '.' . $model->imgFile->extension;
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot') . $filename, false);
                    $model->logo = $filename;
                }
            }
            if($goodsday=GoodsDayCount::findOne(['day'=>date('Y-m-d')])){//判断Goods里day是否有今天的值
                $count=($goodsday->count)+1;//有的情况下,把count里面的值加1
                //str_pad把字符串向右填充新的长度,加上参数STR_PAD_LEFT便是向左填充
                //列如:
//                   $str = "Hello World";
//                   echo str_pad($str,30,".");
//                  输出结果:Hello World...................
//                  即加上Hello World后一共30个个数,不足的用点来补
                $sn=date('Ymd').str_pad($count,4,0,STR_PAD_LEFT);
                $goodsday->count=$count;//把$count的值赋值给$goodsday里面的的count
            }else{
                $sn=date('Ymd').str_pad(1,4,0,STR_PAD_LEFT);
                $goodsday=new GoodsDayCount();
                $goodsday->day=date('Y-m-d');
                $goodsday->count=1;
            }
//            var_dump($model->load(\yii::$app->request->post()));
//            var_dump($model);
//            echo '11111111111111111111111111111';

            $model->sn=$sn;
            $model->create_time=time();
            $model->save();
            $goodsday->save();
            $model3->goods_id = $model->id;
//            var_dump($model->id);
//            var_dump($model3->goods_id);
            $model3->save();
            \Yii::$app->session->setFlash('success','商品添加成功');
            return $this->redirect(['goods/index']);
        }
        $categories = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());//框架的二维遍历语法
        return $this->render('add',['model'=>$model,'categories'=>$categories,'category'=>$count,'model3'=>$model3]);
    }




    //商品的修改
    public function actionEdit($id){
        $model =Goods::findOne($id);
//        var_dump($model->id);
//        $model3 = GoodsIntro::findOne($model->id);
        $model3 = GoodsIntro::findOne(['goods_id'=>$id]);
//        var_dump($model3);exit;
        $model2 = new GoodsDayCount();
        $model->imgFile=UploadedFile::getInstance($model,'imgFile');
        $category=Brand::find()->asArray()->all();
        $count=[];
        foreach($category as $v){
            if($v['status']>=0){
                $count[$v['id']]=$v['name'];
            }
        }
        if($model->load(\yii::$app->request->post())&& $model3->load(\yii::$app->request->post())){
            if($model->validate()) {
                if ($model->imgFile) {
                    $filename = '/images/goods/' . uniqid() . '.' . $model->imgFile->extension;
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot') . $filename, false);
                    $model->logo = $filename;
                }
            }
            if($goodsday=GoodsDayCount::findOne(['day'=>date('Y-m-d')])){//判断Goods里day是否有今天的值
                $count=($goodsday->count)+1;//有的情况下,把count里面的值加1
                //str_pad把字符串向右填充新的长度,加上参数STR_PAD_LEFT便是向左填充
                //列如:
//                   $str = "Hello World";
//                   echo str_pad($str,30,".");
//                  输出结果:Hello World...................
//                  即加上Hello World后一共30个个数,不足的用点来补
                $sn=date('Ymd').str_pad($count,4,0,STR_PAD_LEFT);
                $goodsday->count=$count;//把$count的值赋值给$goodsday里面的的count
            }else{
                $sn=date('Ymd').str_pad(1,4,0,STR_PAD_LEFT);
                $goodsday=new GoodsDayCount();
                $goodsday->day=date('Y-m-d');
                $goodsday->count=1;
            }
//            var_dump($model->load(\yii::$app->request->post()));
//            var_dump($model);
//            echo '11111111111111111111111111111';

            $model->sn=$sn;
            $model->create_time=time();
            $model->save();
            $goodsday->save();
            $model3->goods_id = $model->id;
//            var_dump($model->id);
//            var_dump($model3->goods_id);
            $model3->save();
            \Yii::$app->session->setFlash('success','商品添加成功');
            return $this->redirect(['goods/index']);
        }
        $categories = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());//框架的二维遍历语法
        return $this->render('add',['model'=>$model,'categories'=>$categories,'category'=>$count,'model3'=>$model3]);
    }





    public function actionDelete($id){
        $model = Goods::findOne($id);
        $model -> status = -1;
        $model->save();
        $this->redirect(['goods/index']);
    }

    public function actionDel($id){
        $model = Goods::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('品牌不存在');
        }
        $model->updateAttributes(['status'=>-1]);
        \Yii::$app->session->setFlash('success','品牌删除成功');
        return $this->redirect(['goods/index']);
    }

    public function actions()
{
    return [
        'upload' => [
            'class' => 'kucha\ueditor\UEditorAction',
        ],
    ];
}
}
