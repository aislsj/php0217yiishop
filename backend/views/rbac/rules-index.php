<?=\yii\bootstrap\Html::a('添加权限',['rbac/rules-add'],['class'=>'btn btn-info btn-xs'])?>
<table class="table table-bordered table-responsive">
    <thead>
    <tr>
        <th>权限名称</th>
        <th>权限内容</th>
        <th>操作</th>
    </tr>
    </thead>
    <?php foreach($models as $model):?>
        <tbody>
    <tr>
        <td><?=$model->name?></td>
        <td><?=$model->description?></td>
        <td>
            <?=\yii\bootstrap\Html::a('删除',['rbac/delete-rules','name'=>$model->name],['class'=>'btn btn-danger btn-xs'])?>
            <?=\yii\bootstrap\Html::a('修改',['rbac/edit-rules','name'=>$model->name],['class'=>'btn btn-warning btn-xs'])?>
        </td>
    </tr>
        </tbody>
    <?php endforeach;?>
</table>

<?php

$this->registerCssFile('//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css');
$this->registerJsFile('//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJs('$(".table").DataTable({

});');
