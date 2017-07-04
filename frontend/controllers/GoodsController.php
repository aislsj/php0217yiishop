<?php
namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsIntro;
use backend\models\Photo;
use frontend\models\Address;
use frontend\models\Flow;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;
use Yii;


class GoodsController extends \yii\web\Controller{
    public $layout = 'flow';
    public function actionIndex($goods_id){
        //查找商品详细分类的首分类并添加到主视图上
        $category = GoodsCategory::findAll(['parent_id'=>0]);//分类
        $goods = Goods::findOne(['id'=>$goods_id]);
        $photo = Photo::findAll(['good_id'=>$goods_id]);
        return $this->render('index',['categories'=>$category,'goods'=>$goods,'photos'=>$photo]);
    }

    //添加到购物车
    public function actionAdd(){
        $goods_id = \Yii::$app->request->post('goods_id');//获得商品的id
        $amount = \Yii::$app->request->post('amount');//获得商品的数量
        $goods = Goods::findOne(['id'=>$goods_id]);
        if($goods == null){
            throw new NotFoundHttpException('商品不存在');
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
        }else{//用户登录的情况
            $member_id = \Yii::$app->user->id;
            //先取出之前cookie保存的数据
            //已登录 操作数据库
            //先取出cookie中的数据
            $cookies = Yii::$app->request->cookies;
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
        return $this->redirect('cart.html');
    }
    //回显到页面
    public function actionCart(){
        if(Yii::$app->user->isGuest) {
            //取出cookie中的商品id和数量
            $cookies = Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if ($cookie == null) {
                //cookie中没有购物车数据
                $cart = [];
            }else {
                $cart = unserialize($cookie->value);
            }
            $models = [];
            foreach ($cart as $good_id => $amount) {
                $goods = Goods::findOne(['id' => $good_id])->attributes;
                $goods['amount'] = $amount;
                $models[] = $goods;
            }
    }else{//登录的情况
            $user = \Yii::$app->user->identity;
            $flows  = Flow::findAll(['member_id'=>$user->getId()]);
            foreach ($flows as $flow) {
                $goods = Goods::findOne(['id' => $flow])->attributes;//这里要转换成数组,不然一会不能添加商品数据
                $goods['amount'] = $flow->amount;
                $models[] = $goods;
            }
        }
        return $this->render('flow1', ['models' => $models]);
    }
    //验证cookie中的信息
    public function actionCheckCookie(){
        //取出商品
        $cookie = \Yii::$app->request->cookies;
        $cookie =  $cookie->get('cart');
        if($cookie == null){
            //没有数据的情况
            $cart = [];
        }else{
            $cart = unserialize($cookie->value);
        }
        var_dump($cart);
    }
    //修改cookie中的信息
    public function actionUpdateCart(){
        $goods_id = \Yii::$app->request->post('goods_id');//获得商品的id
        $amount = \Yii::$app->request->post('amount');//获得商品的数量
        $goods = Goods::findOne(['id'=>$goods_id]);
        if($goods == null){
            throw new NotFoundHttpException('商品不存在');
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
            return;
        }
        $models = [];
        foreach($cart as $good_id=>$amount){
            $goods = Goods::findOne(['id'=>$good_id])->attributes;
            $goods['amount']=$amount;
            $models[] = $goods;
        }
    }

    //订单列表
    public function actionOrder()
    {
        $member_id = \Yii::$app->user->id;
        if($member_id){
            $flows = Flow::findAll(['member_id'=>$member_id]);
            $address = Address::findAll(['member_id'=>$member_id]);
            $models = [];
            foreach ($flows as $flow) {
                $goods = Goods::findOne(['id' => $flow])->attributes;//这里要转换成数组,不然一会不能添加商品数据
                $goods['amount'] = $flow->amount;
                $models[] = $goods;
            }
            return $this->render('flow2',['models'=>$models,'address'=>$address]);
        }else{
            return $this->redirect('/member/login.html');
        }
    }

    //添加订单
    public function actionAddOrder(){
        $model = new Order();
        $delivery_id = Yii::$app->request->post('delivery'); //送货方式id
//        var_dump($delivery_id);exit;
        $address_id  = Yii::$app->request->post('address_id'); //地址id

        $num  = Yii::$app->request->post('mouey'); //总金钱
        var_dump($num);exit;
        $pay_id = Yii::$app->request->post('pay');//支付方式id
        $address = Address::findOne(['id'=>$address_id,'member_id'=>Yii::$app->user->id]);
        if($address == null){
            throw new NotFoundHttpException('地址不存在');
        }
        $delis=\frontend\models\Order::getDelivery();
        $pay =\frontend\models\Order::getPayment();
        $memeber = Yii::$app->user->getIdentity();
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
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $model->save();
            //$model->id;//保存后就有id属性
            //订单商品详情表
            //根据购物车数据，把商品的详情查询出来，逐条保存
            $Flow = Flow::findAll(['member_id'=>Yii::$app->user->id]);//购物车
            foreach($Flow as $flow){
                $goods = Goods::findOne(['id'=>$flow->goods_id,'status'=>1]);
                if($goods==null){
                    //商品不存在
                    throw new Exception('商品已售完');
                }
                if($goods->stock< $flow->amount){
                    //库存不足
                    throw new Exception($goods->name.'商品库存不足');
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
                  Flow::deleteAll(['member_id'=>Yii::$app->user->id]);
                $goods->save();
            }
        }catch (Exception $e){
            //回滚
            $transaction->rollBack();
        }
    }

    public function actionSearch(){

    }
}