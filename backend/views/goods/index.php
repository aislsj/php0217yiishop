

<?php
$form = \yii\bootstrap\ActiveForm::begin([
'method' => 'get',
//get方式提交,需要显式指定action
'action'=>\yii\helpers\Url::to(['goods/index']),
'options'=>['class'=>'form-inline']
]);
echo $form->field($model,'name')->textInput(['placeholder'=>'商品名'])->label(false);
echo $form->field($model,'sn')->textInput(['placeholder'=>'货号'])->label(false);
echo $form->field($model,'minPrice')->textInput(['placeholder'=>'最低价'])->label(false);
echo $form->field($model,'maxPrice')->textInput(['placeholder'=>'最高价'])->label('-');
echo \yii\bootstrap\Html::submitButton('搜索',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
?>


 <?=\yii\bootstrap\Html::a('添加',['goods/add'],['class'=>'btn btn-success']);?>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>商品名称</th>
        <th>货号</th>
        <th>LOGO</th>
        <th>商品分类ID</th>
        <th>品牌分类</th>
        <th>市场价格</th>
        <th>商品价格</th>
        <th>库存</th>
        <th>是否在售</th>
        <th>状态</th>
        <th>排序</th>
        <th>添加时间</th>
        <th>操作</th>
    </tr>
    <?php foreach($models as $good):?>
    <?php
    if($good->status >=0 ){
    ?>
        <tr>
            <td><?=$good->id?></td>
            <td><?=$good->name?></td>
            <td><?=$good->sn?></td>
            <td><img src="<?=$good->logo?>" height="50px"></td>
            <td><?=$good->goods_category->name?></td>
            <td><?=$good->brand->name?></td>
            <td><?=$good->market_price?></td>
            <td><?=$good->shop_price?></td>
            <td><?=$good->stock?></td>
            <td><?=($good->is_on_sale)?'在售':'下架'?></td>
            <td><?=($good->status)?'显示':'隐藏'?></td>
            <td><?=$good->sort?></td>
            <td><?=date('Y-m-d',$good->create_time)?></td>
            <td>
                <?php
                if(\Yii::$app->user->can('goods/del'))   echo \yii\bootstrap\Html::a('删除',['goods/del','id'=>$good->id],['class'=>'btn btn-danger btn-xs']);
                if(\Yii::$app->user->can('goods/edit')) echo\yii\bootstrap\Html::a('修改',['goods/edit','id'=>$good->id],['class'=>'btn btn-info btn-xs']);
                if(\Yii::$app->user->can('goods_intro/index')) echo \yii\bootstrap\Html::a('查看',['goods_intro/index','id'=>$good->id],['class'=>'btn btn-success btn-xs']);
                if(\Yii::$app->user->can('photo/gallery'))  echo\yii\bootstrap\Html::a('图片墙',['photo/gallery','id'=>$good->id],['class'=>'btn btn-success btn-xs']);
                }
                ?>
            </td>
        </tr>

    <?php endforeach;?>
</table>