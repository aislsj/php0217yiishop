<?php
namespace frontend\controllers;

use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\models\Address;
use frontend\models\Member;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Response;

class ApiController extends Controller{
    public $enableCsrfValidation = false;//关闭跨网攻击验证

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
        $request = \Yii::$app->request;
        if($request->isPost){
            $model = new Member();
            $model->username=$request->post('username');
            $model->password=$request->post('password');
            $model->tel=$request->post('tel');
            $model->email=$request->post('email');
            if($model->validate()){
                $authManager = \Yii::$app->authManager;
                $model->password_hash  = \Yii::$app->security->generatePasswordHash($model->password);
                $model->save();
                return['status'=>'1','msg'=>'','data'=>$model->toArray()];
            }
            //验证失败
            return ['status'=>'-1','msg'=>$model->getErrors()];
        }
        return ['status'=>'-1','msg'=>'请使用post请求'];
    }
    //登录
    public function actionLogin(){
        $request = \Yii::$app->request;
        if($request->isPost){
            $user = Member::findOne(['username'=>$request->post('username')]);
            if($user && \Yii::$app->security->validatePassword($request->post('password'),$user->password_hash)){
                \Yii::$app->user->login($user);
                return['status'=>'1','msg'=>'登录成功'];
            }
            return['statu'=>'-1','msg'=>'账号或密码错误'];
        }
        return['status'=>'-1','msg'=>'请使用post请求'];
    }
    //获取当前登录用户信息
    public function actionCheckLogin(){
        if(\Yii::$app->user->isGuest){
            return['status'=>'-1','msg'=>'请先登录'];
        }
        return['status'=>'1','msg'=>'','data'=>\Yii::$app->user->identity->toArray()];
    }
    //修改用户密码
    public function actionEditUser(){
            $user = \Yii::$app->user->identity->toArray();
            $request = \Yii::$app->request;
            if($request->isPost){
                $model = Member::findOne(['username'=>$user['username']]);
                if(\Yii::$app->security->validatePassword($request->post('old_password'),$model->password_hash)){
                    $model->password = $request->post('password');
                    $model->password_hash=\Yii::$app->security->generatePasswordHash($request->post('password'));
                    if($model->validate()){
                        $model->save();
                        return['status'=>'1','msg'=>'','data'=>$model->toArray()];
                    }
                    //验证失败
                    return ['status'=>'-1','msg'=>$model->getErrors()];
                }
                //旧密码输入不正确
                return ['status'=>'-1','msg'=>'旧密码输入不正确'];
            }
            return ['status'=>'-1','msg'=>'请使用post请求'];
        }
    //添加地址
    public function actionAddressAdd(){
        $request = \Yii::$app->request;
        $user = \Yii::$app->user->identity->toArray();
        if($request->isPost){
            $address = new Address();
            $address->username=$request->post('username');
            $address->location_detailed=$request->post('location_detailed');
            $address->province=$request->post('province');
            $address->city=$request->post('city');
            $address->area=$request->post('area');
            $address->tel=$request->post('tel');
            $address->member_id=$user['id'];
            $address->is_default=$request->post('is_default');
            if($address->validate()){
                $address->save();
                return['status'=>'1','msg'=>'','data'=>$address->toArray()];
            }
            return['status'=>'-1','msg'=>$address->getErrors()];
        }
        return['status'=>'-1','msg'=>'请使用post请求'];
    }
    //地址修改
    public function actionAddressEdit(){
       $request = \Yii::$app->request;
        $user = \Yii::$app->user->identity->toArray();
        if($request->isPost){
            $address =Address::findOne(['id'=>$request->post('address_id')]);
            $address->username=$request->post('username');
            $address->location_detailed=$request->post('location_detailed');
            $address->province=$request->post('province');
            $address->city=$request->post('city');
            $address->area=$request->post('area');
            $address->tel=$request->post('tel');
            $address->member_id=$user['id'];
            $address->is_default=$request->post('is_default');
            if($address->validate()){
                $address->save();
                return['status'=>'1','msg'=>'','data'=>$address->toArray()];
            }
            return['status'=>'-1','msg'=>$address->getErrors()];
        }
        return['status'=>'-1','msg'=>'请使用post请求'];
    }
    //-删除地址
    public function actionAddressDelete(){
        if($address_id = \Yii::$app->request->get('address_id')){
            $address = Address::findOne(['id'=>$address_id]);
            $address->delete();
            return['status'=>'1','msg'=>'删除成功'];
        }
        return['status'=>'-1','msg'=>'删除失败'];
    }

    //地址列表(当前用户的地址)
    public function actionAddressList(){
        $user = \Yii::$app->user->identity->toArray();
        $address = Address::findAll(['member_id'=>$user['id']]);
        return['status'=>'1','msg'=>'','data'=>$address];
    }

    //获取所有分类
    public function actionGoodsCategory(){
        $GoodsCategory = GoodsCategory::find()->all();
        return['status'=>'1','msg'=>'','data'=>$GoodsCategory];
    }


    //获取某分类下面的所有子分类
    public function actionGoodsCategorySon(){
        $category_id = \Yii::$app->request->get('id');
        $category = GoodsCategory::findOne(['id'=>$category_id]);
        $goods_category = GoodsCategory::find()->andWhere(['tree'=>$category->tree,'lft'<$category->lft,'rgt'>$category->rgt])->all();
        return['status'=>'1','msg'=>'','data'=>$goods_category];
    }

//    -获取某分类的所有商品
    public function actionCategoryGoods(){
        $cate_id = \Yii::$app->request->get('cate_id');
        $goods = Goods::find()->where(['goods_category_id'=>$cate_id])->all();
        return['status'=>'1','msg'=>'','data'=>$goods];
    }
    //获取文章所有分类
    public function actionArticleCategory(){
        $Article = ArticleCategory::find()->all();
        return['status'=>'1','msg'=>'','data'=>$Article];
    }
    //获取某分类下面的所有文章
    public function actionCategoryArticle(){
        $article_category_id = \Yii::$app->request->get('category_id');
        $article_category = Article::findAll(['article_category_id'=>$article_category_id]);
        return['status'=>'1','msg'=>'','data'=>$article_category];
    }
    //-获取某文章所属分类
    public function actionArticleByCategory(){
        $article_id = \Yii::$app->request->get('id');
        $article = Article::findOne(['id'=>$article_id]);
        $category = ArticleCategory::findOne(['id'=>$article->article_category_id]);
        return['status'=>'1','msg'=>'','data'=>$category];
    }
    //解析数据
    public function actionXmal(){
    $simpleXml =simplexml_load_file('http://flash.weather.com.cn/wmaps/xml/chengdu.xml');
//        var_dump($simpleXml->city);exit;
    foreach($simpleXml->city as $cityNode){
        var_dump($cityNode);
        echo ($cityNode['cityname'].'==='.$cityNode['stateDetailed'].'<br/>');

    }






















    }
}