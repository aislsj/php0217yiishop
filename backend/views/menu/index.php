<?php
   echo  \yii\bootstrap\Html::a('添加菜单',['menu/add'],['class'=>'btn btn-info'])
?>
<table class="table table-bordered">
    <tr>
        <th>id</th>
        <th>菜单名称</th>
        <th>地址/路由</th>

        <th>排序</th>
        <th>操作</th>
    </tr>
    <?php foreach($models as $model):?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->label?></td>
        <td><?=$model->url?></td>
        <td><?=$model->sort?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',['menu/edit', 'id'=>$model->id],['class'=>'btn btn-info btn-xs'])?>
            <?=\yii\bootstrap\Html::a('删除',['menu/delete','id'=>$model->id], ['class'=>'btn btn-danger btn-xs'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>