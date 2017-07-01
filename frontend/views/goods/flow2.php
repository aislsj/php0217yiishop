<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

//加载静态资源管理器，注册静态资源到当前布局文件
\frontend\assets\FlowAsset::register($this);
?>



<div style="clear:both;"></div>

<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="index.html"><?=Html::img('@web/images/logo.png')?></a></h2>
        <div class="flow fr flow2">
            <ul>
                <li>1.我的购物车</li>
                <li class="cur">2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->

<div style="clear:both;"></div>

<!-- 主体部分 start -->
<div class="fillin w990 bc mt15">
    <div class="fillin_hd">
        <h2>填写并核对订单信息</h2>
    </div>
     <form action="<?=\yii\helpers\Url::to(['goods/add-order'])?>" id="ais" method="post">
         <input name="_csrf-frontend" type="hidden" value="<?=Yii::$app->request->getCsrfToken()?>">
    <div class="fillin_bd">
        <!-- 收货人信息  start-->
        <div class="address">
            <h3>收货人信息</h3>
            <div class="address_info">
                    <?php foreach($address as $ads):?>
                 <p><input type="radio" value="<?=$ads->id?>" name="address_id" <?= ($ads->is_default)?'checked="checked"':''  ?>/><?=$ads->username?>&nbsp;&nbsp;&nbsp;<?=$ads->tel?>&nbsp;&nbsp;&nbsp;<?=$ads->province?>&nbsp;&nbsp;&nbsp;<?=$ads->city?>&nbsp;&nbsp;&nbsp;<?=$ads->area?>&nbsp;&nbsp;&nbsp;<?=$ads->location_detailed?></p>
                    <?php endforeach;?>
            </div>
        </div>
        <!-- 收货人信息  end-->

        <!-- 配送方式 start -->
        <div class="delivery">
            <h3>送货方式 </h3>
            <div class="delivery_select">
                <table>
                    <thead>
                    <tr>
                        <th class="col1">送货方式</th>
                        <th class="col2">运费</th>
                        <th class="col3">运费标准</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $delis=\frontend\models\Order::getDelivery();
                    foreach($delis as $k=>$deli):?>
                    <tr <?=($k)?'':'class="cur"' ?>>
                        <td>
                            <input type="radio" value="<?=$deli['id']?>" name="delivery" class="ais_price" data_price="<?=$deli['price']?>" <?=($k)?'':'checked=""checked'?>/>
                            <?=$deli['name']?>
                        </td>
                            <td><?=$deli['price']?></td>
                            <td><?=$deli['intro']?></td>
                    </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- 配送方式 end -->
        <!-- 支付方式  start-->
        <div class="pay">
            <h3>支付方式 </h3>
            <div class="pay_select">
                <table>
                    <?php
                    $payments =\frontend\models\Order::getPayment();
                    foreach($payments as $k=>$payment):?>
                    <tr <?=($k)?'':'class="cur"'?>>
                        <td class="col1"><input type="radio" name="pay" value="<?=$payment['id']?>"  <?=($k)?'':'checked="checked"'?>/>
                            <?=$payment['name']?>
                        </td>
                        <td class="col2"><?=$payment['intro']?></td>
                    <?php endforeach;?>
                </table>

            </div>
        </div>
        <!-- 支付方式  end-->
        <!-- 发票信息 start-->
        <div class="receipt none">
            <h3>发票信息 </h3>
            <div class="receipt_select ">
                <form action="">
                    <ul>
                        <li>
                            <label for="">发票抬头：</label>
                            <input type="radio" name="type" checked="checked" class="personal" />个人
                            <input type="radio" name="type" class="company"/>单位
                            <input type="text" class="txt company_input" disabled="disabled" />
                        </li>
                        <li>
                            <label for="">发票内容：</label>
                            <input type="radio" name="content" checked="checked" />明细
                            <input type="radio" name="content" />办公用品
                            <input type="radio" name="content" />体育休闲
                            <input type="radio" name="content" />耗材
                        </li>
                    </ul>
                </form>
            </div>
        </div>
        <!-- 发票信息 end-->
        <!-- 商品清单 start -->
        <div class="goods">
            <h3>商品清单</h3>
            <table>
                <thead>
                <tr>
                    <th class="col1">商品</th>
                    <th class="col3">价格</th>
                    <th class="col4">数量</th>
                    <th class="col5">小计</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($models as $good):?>
                    <?php
                    $num = 0;
                    $num += $good['shop_price']*$good['amount'] ;
                    ?>
                <tr>

                    <td class="col1"><a href=""><?=Html::img('http://admin.yii2shop.com'.$good['logo'])?></a>
                        <strong><a href=""><?=$good['name']?></a></strong></td>
                    <td class="col3"><?=$good['shop_price']?></td>
                    <td class="col4"><?=$good['amount']?></td>
                    <td class="col5"><span><?=($good['shop_price']*$good['amount'] )?></span></td>
                </tr>
                <?php endforeach?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <ul>
                            <li>
                                <?php
                                $num=0;
                                $number = 0;
                                foreach($models as $good):
                                    $number += $good['amount'];
                                    $num += $good['shop_price'] * $good['amount'] ;
                                endforeach;
                                echo '<span>'.$number.'件商品，总商品金额：</span>'.'<em>￥'.$num.'</em>';
                                ?>
                            </li>
                            <li>
                                <span>运费：</span>
                                <em>￥<span class="yufei">10</span></em>
                            </li>
                            <li>
                                <span>应付总额：</span>
                                <input type="hidden" name="mouey" value="<?php echo $num?>">
                                <em>￥<span class="total_prices" price="<?php echo $num;?>" ><?php echo ($num+10);?></span></em>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- 商品清单 end -->
    </div>
    <div class="fillin_ft">


        <a href="javascript:;" id="submit" onclick="document.getElementById('ais').submit()">
            <span>提交订单</span>
        </a>
        <p>应付总额：<strong><?php echo $num?></strong></p>

    </div>
   </form>
</div>
<!-- 主体部分 end -->

<div style="clear:both;"></div>
<?php
$url = \yii\helpers\Url::to(['goods/order']);
$token = Yii::$app->request->csrfToken;
$this->registerJs(new \yii\web\JsExpression(
<<<Js
    //监听送货方式的点击事件
    $(".ais_price").click(function(){
     var price = Number($(this).attr('data_price'));//获得运费
     $('.yufei').empty();//清空内容
     $('.yufei').text(price);//填充内容
     var total_prices = Number($(".total_prices").attr('price'));//获得总价
     $('.total_prices').empty();//清空总价
     var all_prices = total_prices+price;//把总价和运费相加
     $('.total_prices').text(all_prices);//填充内容
    })
Js
));

