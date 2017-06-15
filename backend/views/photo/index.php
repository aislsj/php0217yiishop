<?=\yii\bootstrap\Html::a('添加图片',['photo/add','id'=>$model2],['class'=>'btn btn-info btn-xs'])?>
<?=\yii\bootstrap\Html::a('返回上一级',['goods/index'],['class'=>'btn btn-success btn-xs'])?>
<table class="table table-bordered">
    <tr>
        <th>图片</th>
        <th>操作</th>
    </tr>
    <?php foreach($model as $img):?>
    <tr>
        <td class="col-lg-10"><img src="<?=$img->url?>" height="150"> </td>
        <td>
            <?=\yii\bootstrap\Html::a('删除',['photo/delete','id'=>$img->id,'id2'=>$model2],['class'=>'btn btn-success btn-xs'])?>
            <?=\yii\bootstrap\Html::a('修改',['photo/edit','id'=>$img->id],['class'=>'btn btn-info btn-xs'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>