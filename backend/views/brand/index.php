<?=\yii\bootstrap\Html::a('添加',['brand/add'],['class'=>'btn btn-success'])?>
<table class="table table-bordered">
    <tr>
        <th class="col-lg-1">ID</th>
        <th class="col-lg-1">品牌名字</th>
        <th class="col-lg-3">内容</th>
        <th class="col-lg-2">LOGO</th>
        <th class="col-lg-1">排序</th>
        <th class="col-lg-1">状态</th>
        <th class="col-lg-3">操作</th>
    </tr>
    <?php foreach($models as $brand):?>
    <tr>
        <?php
            if($brand->status >=0 ){
         ?>
                <td><?=$brand->id?></td>
                <td><?=$brand->name?></td>
                <td><?=$brand->intro?></td>
                <td><img src="<?=$brand->logo?>" width="80px"></td>
                <td><?=$brand->sort?></td>
                <td><?=($brand->status)?'正常':'隐藏'?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('修改',['brand/edit','id'=>$brand->id],['class'=>'btn btn-info btn-xs'])?>
                    <?=\yii\bootstrap\Html::a('删除',['brand/delete','id'=>$brand->id],['class'=>'btn btn-danger btn-xs'])?>
                </td>
        <?php
            }
        ?>

    </tr>
    <?php endforeach;?>
</table>