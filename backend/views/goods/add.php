<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
//echo $form->field($model,'goods_category_id');
echo $form->field($model, 'goods_category_id')->hiddenInput();//商品分类ID隐藏域
echo '<ul id="treeDemo" class="ztree"></ul>';//商品分类树状图
echo $form->field($model3,'content')->widget('kucha\ueditor\UEditor',[]);//内容详情,百度编辑器
echo $form->field($model,'brand_id')->dropDownList($category,['prompt'=>'=请选择分类=']);//品牌
echo $form->field($model,'market_price');//价格
echo $form->field($model,'shop_price');//价格
echo $form->field($model,'stock');//库存
echo $form->field($model,'is_on_sale')->radioList([1=>'在售',0=>'下架']);
echo $form->field($model,'status')->radioList([1=>'正常',0=>'隐藏']);
echo $form->field($model,'sort');//排序
//echo $form->field($model,'imgFile')->fileInput(['id'=>'test']);
echo $form->field($model,'imgFile')->fileInput();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();


//使用Ztree
//    <link rel="stylesheet" href="/zTree/css/demo.css" type="text/css">
//    <link rel="stylesheet" href="/zTree/css/zTreeStyle/zTreeStyle.css" type="text/css">
//    <script type="text/javascript" src="/zTree/js/jquery-1.4.4.min.js"></script>
//    <script type="text/javascript" src="/zTree/js/jquery.ztree.core.js"></script>

$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
//$this->registerCssFile('@web/zTree/css/demo.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);



$zNodes = \yii\helpers\Json::encode($categories);
$js = new \yii\web\JsExpression(
    <<<JS
     var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
 var setting = {
    data: {
        simpleData: {
            enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                }
            },
            callback:{
                onClick:function(event,treeId,treeNode){
                    //console.log(treeNode.id);
                    //将选择节点的id赋值给表单parent_id
                    $("#goods-goods_category_id").val(treeNode.id);
                }
            }


        };

        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
      var zNodes ={$zNodes};
        zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        zTreeObj.expandAll(true);//设置默认展开全部节点 包括子节点

JS
);
$this->registerJs($js);
