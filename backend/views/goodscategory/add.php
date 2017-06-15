<?php
/**
 * @var $this \yii\web\View
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
//echo $form->field($model,'parent_id')->dropDownList($options);
echo $form->field($model, 'parent_id')->hiddenInput();
echo '<ul id="treeDemo" class="ztree"></ul>';
echo $form->field($model,'intro')->textarea();
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
                    $("#goodscategory-parent_id").val(treeNode.id);
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
