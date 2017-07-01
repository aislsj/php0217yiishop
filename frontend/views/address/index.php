<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

//加载静态资源管理器，注册静态资源到当前布局文件
\frontend\assets\AddressAsset::register($this);
?>



    <div style="clear:both;"></div>

  <!-- 导航条部分 start -->
<div class="nav w1210 bc mt10">
    <!--  商品分类部分 start-->
    <div class="category fl cat1"> <!-- 非首页，需要添加cat1类 -->
        <div class="cat_hd off">  <!-- 注意，首页在此div上只需要添加cat_hd类，非首页，默认收缩分类时添加上off类，鼠标滑过时展开菜单则将off类换成on类 -->
            <h2>全部商品分类</h2>
            <em></em>
        </div>

        <div class="cat_bd none">
            <?php foreach ($categories as $k=>$category)://遍历所有一级分类?>
                <div class="cat <?=$k==0?"item1":""?>">
                    <h3><?=Html::a($category->name,['list/index','cate_id'=>$category->id])?><b></b></h3>
                    <div class="cat_detail none">
                        <?php foreach($category->children as $k2=>$child):?>
                            <dl <?=$k2==0?'class="dl_1st"':''?>>
                                <dt><?=Html::a($child->name,['list/index','cate_id'=>$child->id])?></dt>
                                <dd>
                                    <?php foreach($child->children as $cate):?>
                                        <?=Html::a($cate->name,['list/index','cate_id'=>$cate->id])?>
                                    <?php endforeach;?>
                                </dd>
                            </dl>
                        <?php endforeach;?>
                    </div>
                </div>
            <?php endforeach;?>
        </div>
    </div>
    <!--  商品分类部分 end-->

    <div class="navitems fl">
        <ul class="fl">
            <li class="current"><a href="">首页</a></li>
            <li><a href="">电脑频道</a></li>
            <li><a href="">家用电器</a></li>
            <li><a href="">品牌大全</a></li>
            <li><a href="">团购</a></li>
            <li><a href="">积分商城</a></li>
            <li><a href="">夺宝奇兵</a></li>
        </ul>
        <div class="right_corner fl"></div>
    </div>
</div>
<!-- 导航条部分 end -->
</div>
<!-- 头部 end-->

<div style="clear:both;"></div>

<!-- 页面主体 start -->
<div class="main w1210 bc mt10">
    <div class="crumb w1210">
        <h2><strong>我的XX </strong><span>> 我的订单</span></h2>
    </div>

    <!-- 左侧导航菜单 start -->
    <div class="menu fl">
        <h3>我的XX</h3>
        <div class="menu_wrap">
            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">我的订单</a></dd>
                <dd><b>.</b><a href="">我的关注</a></dd>
                <dd><b>.</b><a href="">浏览历史</a></dd>
                <dd><b>.</b><a href="">我的团购</a></dd>
            </dl>

            <dl>
                <dt>账户中心 <b></b></dt>
                <dd class="cur"><b>.</b><a href="">账户信息</a></dd>
                <dd><b>.</b><a href="">账户余额</a></dd>
                <dd><b>.</b><a href="">消费记录</a></dd>
                <dd><b>.</b><a href="">我的积分</a></dd>
                <dd><b>.</b><a href="">收货地址</a></dd>
            </dl>

            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">返修/退换货</a></dd>
                <dd><b>.</b><a href="">取消订单记录</a></dd>
                <dd><b>.</b><a href="">我的投诉</a></dd>
            </dl>
        </div>
    </div>
    <!-- 左侧导航菜单 end -->

    <!-- 右侧内容区域 start -->
    <div class="content fl ml10">
        <div class="address_hd">
            <h3>收货地址薄</h3>
          <?php foreach($models2 as $model2):?>
            <dl>
                <dt><?=$model2->id?>&nbsp;&nbsp;<?=$model2->username?>&nbsp;&nbsp;<?=$model2->province?>&nbsp;&nbsp;<?=$model2->city?>
                    &nbsp;<?=$model2->area?>&nbsp;&nbsp;<?=$model2->location_detailed?>&nbsp;&nbsp;<?=$model2->tel?>
                </dt>
                <dd>
                    <?=\yii\bootstrap\Html::a('修改',['address/edit','id'=>$model2->id])?>
                    <?=\yii\bootstrap\Html::a('删除',['address/delete','id'=>$model2->id])?>
                    <?=\yii\bootstrap\Html::a('设为默认地址',['address/isdefault','member_id'=>$model2->member_id,'id'=>$model2->id])?>

                </dd>
            </dl>
            <?php endforeach;?>
        </div>

        <div class="address_bd mt10">
            <h4>新增收货地址</h4>
            <?php
            $form = \yii\widgets\ActiveForm::begin(
                ['fieldConfig'=>[
                    'options'=>[
                        'tag'=>'li',
                    ],
                    'errorOptions'=>[
                        'tag'=>'p'
                    ]
                ]]
            );
            echo '<ul>';
            echo $form->field($model,'username')->textInput(['class'=>'txt']);//收件人姓名
            echo '<li><label for="">所在地区:</label> </li>';
            echo $form->field($model,'province',['template'=>"{input}",'options'=>['tag'=>false]])->dropDownList([''=>'=选择省=']);
            echo $form->field($model,'city',['template'=>"{input}",'options'=>['tag'=>false]])->dropDownList([''=>'=选择市=']);
            echo $form->field($model,'area',['template'=>"{input}",'options'=>['tag'=>false]])->dropDownList([''=>'=选择县=']);
            echo $form->field($model,'location_detailed')->textInput(['class'=>'txt address']);
            echo $form->field($model,'tel')->textInput(['class'=>'txt']);
            echo '<li>
        							<label for="">&nbsp;</label>
        							<input type="checkbox" class="chb" name="Address[is_default]" value="1"/> 设为默认地址
        		  </li>';
            echo '<li>
                        <label for="">&nbsp;</label>
                        <input type="submit" value="保存" class="btn">
                    </li>';

            echo '</ul>';
            \yii\widgets\ActiveForm::end();
            ?>


    </div>
    <!-- 右侧内容区域 end -->
</div>
<!-- 页面主体 end-->

<div style="clear:both;"></div>


<?php
$this->registerJsFile('@web/js/address.js');
$this->registerJs(new \yii\web\JsExpression(
    <<<js
    //遍历加载静态资源的省的数据
    $(address).each(function(){
    var option = '<option value="'+this.name+'">'+this.name+'</option>';
    $("#address-province").append(option);
});
        //切换（选中）省，读取该省对应的市，更新到市下拉框
    $("#address-province").change(function(){
        var province = $(this).val();//获取当前选中的省
        //console.log(province);
        //获取当前省对应的市 数据
        $(address).each(function(){
            if(this.name == province){
                var option = '<option value="">=请选择市=</option>';
                $(this.city).each(function(){
                    option += '<option value="'+this.name+'">'+this.name+'</option>';
                });
                $("#address-city").html(option);
            }
        });
        //将县的下拉框数据清空
        $("#address-county").html('<option value="">=请选择县=</option>');
    });
     //切换（选中）市，读取该市 对应的县，更新到县下拉框
    $("#address-city").change(function(){
        var city = $(this).val();//当前选中的城市
        $(address).each(function(){
            if(this.name == $("#address-province").val()){
                $(this.city).each(function(){
                    if(this.name == city){
                        //遍历到当前选中的城市了
                        var option = '<option value="">=请选择县=</option>';
                        $(this.area).each(function(i,v){
                            option += '<option value="'+v+'">'+v+'</option>';
                        });
                        $("#address-area").html(option);
                    }
                });
            }
        });
    });
js
));
$js = '';
if($model->province){
    $js .= '$("#address-province").val("'.$model->province.'");';
}
if($model->city){
    $js .= '$("#address-province").change();$("#address-city").val("'.$model->city.'");';
}
if($model->area){
    $js .= '$("#address-city").change();$("#address-area").val("'.$model->area.'");';
}
$this->registerJs($js);


