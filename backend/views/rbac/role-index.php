<?php echo \yii\bootstrap\Html::a('添加角色',['rbac/role-add'],['class'=>'btn btn-info btn-xs'])?>
<table class="table table-bordered table-responsive">

    <tr>
        <th>角色名称</th>
        <th>角色描述</th>
        <th class="col-lg-7">角色权限</th>
        <th>操作</th>
    </tr>
    </thead>
    <?php foreach($model as $model):?>
        <tbody>
    <tr>
        <td><?=$model->name?></td>
        <td><?=$model->description?></td>
        <td><?php
//            var_dump($model->name);
            foreach (Yii::$app->authManager->getPermissionsByRole($model->name) as $permission){
                echo $permission->description;
                echo '&nbsp&nbsp';
            }
            ?>
        </td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',['rbac/edit-role','name'=>$model->name],['class'=>'btn btn-warning btn-xs'])?>
            <?=\yii\bootstrap\Html::a('删除',['rbac/del-role','name'=>$model->name],['class'=>'btn btn-danger btn-xs'])?></td>
        </tr>
        </tbody>
    <?php endforeach;?>
</table>

