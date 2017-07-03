<?php
namespace frontend\controllers;

use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\Order;
use Flc\Alidayu\App;
use Flc\Alidayu\Client;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use frontend\models\Address;
use frontend\models\Flow;
use frontend\models\Member;
use frontend\models\OrderGoods;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\Response;
use yii\web\UploadedFile;

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
    //退出
    public function actionLogout(){
        \Yii::$app->user->logout();
        return ['status'=>'1','msg'=>'退出成功'];
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
        if($category_id){
            return['status'=>'-1','msg'=>'该类商品不存在'];
        }
        $category = GoodsCategory::findOne(['id'=>$category_id]);
        $tree = $category->tree;//传过来的分类的树
        $lft = $category->lft;//传过来分类的左值
        $rgt = $category->rgt;//传过来分类的右值
        $goods_category = GoodsCategory::find()->where(['tree'=>$tree])->andWhere(['>','lft',$lft])->andWhere(['<','rgt',$rgt])->all();
           return['status'=>'1','msg'=>'','data'=>$goods_category];
    }

    //-获取某分类的父分类
    public function actionGoodsCategoryParent(){
        $category_id = \Yii::$app->request->get('id');
        $category = GoodsCategory::findOne(['id'=>$category_id]);
        $parent_category = GoodsCategory::findOne(['id'=>$category->parent_id]);
        return['status'=>'-1','msg'=>'','data'=>$parent_category];
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
    //添加商品到购物车
    public function actionAddCart(){
        $goods_id = \Yii::$app->request->post('goods_id');//获得商品的id
        $amount = \Yii::$app->request->post('amount');//获得商品的数量
        $goods = Goods::findOne(['id'=>$goods_id]);
        if($goods == null){
           return['status'=>'-1','msg'=>'商品不存在'];
        }
        if(\Yii::$app->user->isGuest){
            //未登录
            //先获取购物车的数据
            $cookie = \Yii::$app->request->cookies;
            $cookie =  $cookie->get('cart');
            if($cookie == null){
                //没有数据的情况
                $cart = [];
            }else{
                $cart = unserialize($cookie->value);//反序列化
            }
            //将商品数量存到cookie
            $cookies = \Yii::$app->response->cookies;
            //检测购物车中是否有该商品
            if(key_exists($goods->id,$cart)){
                $cart[$goods_id] += $amount;
            }else{
                $cart[$goods_id] = $amount;
            }
            $dataObject = new Cookie([
                'name'=>'cart','value'=>serialize($cart)//序列化数组,不然无法保存到cookie
            ]);
            $cookies->add($dataObject);
            return['status'=>'1','msg'=>'已将商品保存到购物车,请尽快登录!'];
        }else{//用户登录的情况
            $member_id = \Yii::$app->user->id;
            //先取出之前cookie保存的数据
            //已登录 操作数据库
            //先取出cookie中的数据
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if ($cookie == null) {
                //cookie中没有购物车数据
                $cart = [];
            }else {
                $cart = unserialize($cookie->value);
                //遍历cookie并保存到数据库-----------------
                foreach($cart as $key => $good){
                    $ais = Flow::findOne(['member_id'=>$member_id,'goods_id'=>$key]);
                    if($ais){
                        $ais->amount += $amount;
                        $ais->save();
                    }else{
                        $model = new Flow();
                        $model->goods_id= $key;
                        $model->amount= $good;
                        $model->member_id =  $member_id ;
                        $model->save();
                    }
                }
                \yii::$app->response->cookies->remove('cart');
            }
            //cookie保存数据库结束-----------------
            $good = Flow::findOne(['member_id'=>$member_id,'goods_id'=>$goods_id]);
            if($good){
                $good->amount += $amount;
                $good->save();
            }else{
                $model = new Flow();
                $model->goods_id=$goods_id;
                $model->amount=$amount;
                $model->member_id =  $member_id ;
                $model->save();
            }
        }
        return['status'=>'1','msg'=>'添加成功'];
    }

    //修改购物车中的商品数量
    public function actionEditCart(){
        $goods_id = \Yii::$app->request->post('goods_id');//获得商品的id
        $amount = \Yii::$app->request->post('amount');//获得商品的数量
        $goods = Goods::findOne(['id'=>$goods_id]);
        if($goods == null){
            return['status'=>'-1','msg'=>'商品不存在'];
        }
        if(\Yii::$app->user->isGuest){
            //未登录
            //先获取购物车的数据
            $cookie = \Yii::$app->request->cookies;
            $cookie =  $cookie->get('cart');
            if($cookie == null){
                //没有数据的情况
                $cart = [];
            }else{
                $cart = unserialize($cookie->value);
            }
            //将商品数量存到cookie
            $cookies = \Yii::$app->response->cookies;
            if($amount){
                $cart[$goods_id] = $amount;
            }else{//如果购物车中没有有商品,才删掉
                if(key_exists($goods->id,$cart))unset($cart[$goods_id]);
            }
            $dataObject = new Cookie([
                'name'=>'cart','value'=>serialize($cart)//序列化数组,不然无法保存到cookie
            ]);
            $cookies->add($dataObject);
            return['status'=>'1','msg'=>'已将商品保存到购物车,请尽快登录!'];
        }else{
            //登录的情况
            //已登录 操作数据库
            $member_id = \Yii::$app->user->id;
            $good = Flow::findOne(['member_id'=>$member_id,'goods_id'=>$goods_id]);
            if($amount>0){
                $good->amount = $amount;
                $good->save();
            }else{
                $good->delete();
            }
            $flows  = Flow::findAll(['member_id'=>$member_id]);
            $models = [];
            foreach ($flows as $flow) {
                $goods = Goods::findOne(['id' => $flow])->attributes;//这里要转换成数组,不然一会不能添加商品数据
                $goods['amount'] = $flow->amount;
                $models[] = $goods;
            }
            return['status'=>'1','msg'=>'修改成功'];
        }
    }
    //删除购物车的某商品
    public function actionDeleteCart(){
        $id = Flow::findOne(['id'=>\Yii::$app->request->post('goods_id'),'member_id'=>\Yii::$app->user->id]);
        if($id){
            $id->delete();
            return['status'=>'1','msg'=>'删除成功'];
        }else{
            return['status'=>'-1','msg'=>'删除失败,商品不存在'];
        }
    }
    //一键清空购物车
    public function actionDeleteCartAll(){
        Flow::deleteAll(['member_id'=>\Yii::$app->user->id]);
        return['status'=>'1','msg'=>'删除成功'];
    }
    //获取购物车的所有商品
    public function actionShowCart(){
        $cart = Flow::findAll(['member_id'=>\Yii::$app->user->id]);
        return['status'=>'1','msg'=>'','data'=>$cart];
    }
    //获取当前用户订单列表
    public function actionShowOrder(){

    }
    //-获取送货方式
        public function actionShowDelivery(){
        $delis=\frontend\models\Order::getDelivery();
        return['status'=>'1','msg'=>'','data'=>$delis];
    }
    //-获取支付方式
    public function actionShowPayment(){
        $payments =\frontend\models\Order::getPayment();
        return['status'=>'1','msg'=>'','data'=>$payments];
    }
    //提交订单
    public function actionAddOrder(){
        $model = new Order();
        $delivery_id = \Yii::$app->request->post('delivery'); //送货方式id
        $address_id  = \Yii::$app->request->post('address_id'); //地址id
        $num  = \Yii::$app->request->post('mouey'); //总金额
        $pay_id = \Yii::$app->request->post('pay');//支付方式id
        $address = Address::findOne(['id'=>$address_id,'member_id'=>\Yii::$app->user->id]);
        if($address == null){
            return['status'=>'-1','msg'=>'地址不存在'];
        }
        $delis=\frontend\models\Order::getDelivery();
        $pay =\frontend\models\Order::getPayment();
        $memeber = \Yii::$app->user->getIdentity();
        $model->member_id = $memeber->id;
        $model->name = $address->username;
        $model->province = $address->province;
        $model->city = $address->city;
        $model->area= $address->area;
        $model->address = $address->location_detailed;
        $model->tel = $memeber->tel;
        $model->delivery_id =  $delis[$delivery_id-1]['id'];
        $model->delivery_name = $delis[$delivery_id-1]['name'];
        $model->delivery_price = $delis[$delivery_id-1]['price'];
        $model->payment_id = $pay[$pay_id-1]['id'];
        $model->payment_name = $pay[$pay_id-1]['name'];
        $model->create_time = time();
        $model->status = 1;
        $model->total= ($num - $delis[$delivery_id-1]['price']);
        //开启事务
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $model->save();
            //$model->id;//保存后就有id属性
            //订单商品详情表
            //根据购物车数据，把商品的详情查询出来，逐条保存
            $Flow = Flow::findAll(['member_id'=>\Yii::$app->user->id]);//购物车
            foreach($Flow as $flow){
                $goods = Goods::findOne(['id'=>$flow->goods_id,'status'=>1]);
                if($goods==null){
                    //商品不存在
                    return['sataus'=>'-1','msg'=>'商品已售完'];
                }
                if($goods->stock< $flow->amount){
                    //库存不足
                    return['sataus'=>'-1','msg'=>'商品库存不足'];
                }
                $order_goods = new OrderGoods();
                $order_goods->order_id = $model->id;
                $order_goods->goods_id = $goods->id;
                $order_goods->goods_name = $goods->name;
                $order_goods->logo = $goods->logo;
                $order_goods->price = $goods->shop_price;
                $order_goods->amount = $flow->amount;
                $order_goods->total = $order_goods->price*$order_goods->amount;
                $order_goods->save();
                //扣库存 //扣减该商品库存
                $goods->stock -= $flow->amount;
                //创建订单和删除保存的商品数据
                Flow::deleteAll(['member_id'=>\Yii::$app->user->id]);
                $goods->save();
            }
        }catch (Exception $e){
            //回滚
            $transaction->rollBack();
        }
        return['sataus'=>'1','msg'=>'已为你创建订单!'];
    }
    //删除订单
    public function actionDeleteOrder(){
//        $delivery_id = \Yii::$app->request->post('delivery'); //送货方式id
        $order_id = \Yii::$app->request->get('order_id');
        $Order = Order::findOne(['id'=>$order_id]);
        OrderGoods::deleteAll(['order_id'=>$Order->id]);
        $Order->delete();
        return['sataus'=>'1','msg'=>'删除成功'];
    }









/*
 * 高级api
 */

        //验证码
        public function actions(){
            return[
                'captcha' => [
                    'class' => 'yii\captcha\CaptchaAction',
                    'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                    'minLength'=>4,
                    'maxLength'=>4,
                 ]
            ];
        }

        //文件上传

    public function actionUpload(){
        $img = UploadedFile::getInstanceByName('img');
        if($img){
            $ais = '/upload/'.uniqid().'.'.$img->extension;
            $re = $img->saveAs(\Yii::getAlias('@webroot').$ais,0);
            if($re){
                return['status'=>'1','msg'=>'','data'=>$ais];
            }else{
                return['status'=>'-1','msg'=>$img->error];
            }
        }else{
                return['status'=>'-1','msg'=>'没有文件上传'];
        }


    }
        //分页读取数据
//    public function actionList(){
//        //每页显示条数
//        $page_size = \Yii::$app->request->get('page_size',2);
//        //当前第几页(最小为1)
//        $page = \Yii::$app->request->get('page',1);
//        $page = $page<1?1:$page;
//        //所有数据
//        $qurey =  Goods::find();
//        //总条数
//        $total =$qurey->count();
//        //商品数据
//        $goods = $qurey->offset($page_size*($page-1))->limit($page_size)->asArray()->all();
//        return['status'=>'1','msg'=>'','data'=>[
//           'tatol'=>$total,
//            'page_size'=>$page_size,
//            'page'=>$page,
//            'goods'=>$goods
//        ]];
//    }

    //分页读取数据(查询)
    public function actionList(){
        //每页显示条数
        $page_size = \Yii::$app->request->get('page_size',2);
        //当前第几页(最小为1)
        $page = \Yii::$app->request->get('page',1);
        $page = $page<1?1:$page;
        //所有数据
        $qurey =  Goods::find();
        //总条数
        $total =$qurey->count();
        //查询条件
        $keywords = \Yii::$app->request->get('keywords');
        if($keywords){
            $qurey->andWhere(['like','name',$keywords]);
        }
        //商品数据
        $goods = $qurey->offset($page_size*($page-1))->limit($page_size)->asArray()->all();
        return['status'=>'1','msg'=>'','data'=>[
            'tatol'=>$total,
            'page_size'=>$page_size,
            'page'=>$page,
            'goods'=>$goods
        ]];
    }


        //发送手机验证码
        public function actionTelCode(){
            // 配置信息
            $config = [
                'app_key'    => '24478575',//阿里大于中自己申请的参数
                'app_secret' => '76a31b5a1d588ca9a3903c489e5be00d',//阿里大于中自己申请的参数
                // 'sandbox'    => true,  // 是否为沙箱环境，默认false
            ];
            //确保上一次发送短信间隔超过1分钟
            $tel = \Yii::$app->request->post('tel');
            //用正则判断电话号码
            if(!preg_match('/^1[34578]\d{9}$/',$tel)){
                return ['status'=>'-1','msg'=>'电话号码不正确'];
            }
            //检测上次发送时间
            $value = \Yii::$app->cache->get('tel_',$tel);
            $s = time()-$value;
            if($s<60){
                return['status'=>'-1','msg'=>'请'.(60-$s).'秒后在试'];
            }

            $code = rand(1000,9999);//将要发送的随机码
            // 使用方法一
            $client = new Client(new App($config));
            $req    = new AlibabaAliqinFcSmsNumSend;
            $req->setRecNum($tel)//用户的电话号码
            ->setSmsParam([
                'code' => $code//发送的验证码\code为自己模板的内容
            ])
                ->setSmsFreeSignName('李双杰的网站')//设置的短信签名
                ->setSmsTemplateCode('SMS_71480187');//设置的短信模板ID
            $result = $client->execute($req);//发送到阿里云
            if($result){
                //保存当前验证码 session  mysql  redis  不能保存到cookie
                \Yii::$app->cache->set('tel_'.$tel,$code,5*60);
                \Yii::$app->cache->set('time_tel_'.$tel,time(),5*60);
//            echo 'success'.$code;//在控制台查看验证码
                return['status'=>'1','msg'=>'','data'=>$code];
            }else{
                return['status'=>'-1','msg'=>'发送失败'];
            }
        }


    //微信-------------
    //解析数据
    public function actionXmal(){
        $simpleXml =simplexml_load_file('http://flash.weather.com.cn/wmaps/xml/chengdu.xml');
//        var_dump($simpleXml->city);exit;
        foreach($simpleXml->city as $cityNode){
            var_dump($cityNode);
            echo ($cityNode['cityname'].'==='.$cityNode['stateDetailed'].'<br/>');
        }}








}