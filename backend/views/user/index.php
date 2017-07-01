<?=\yii\bootstrap\Html::a('添加用户',['user/add'],['class'=>'btn btn-info btn-xs'])?>
<table class="table table-bordered">
    <tr>
        <th>Id</th>
        <th>用户名</th>
        <th>用户身份</th>
        <th>邮箱</th>
        <th>最后登录时间</th>
        <th>最后登录IP</th>
        <th>操作</th>
    </tr>
    <?php foreach($model as $modes):?>
    <tr>
        <th><?=$modes->id?></th>
        <th><?=$modes->username?></th>

        <th>
            <?php
            $ais = Yii::$app->authManager->getRolesByUser($modes->id);
            foreach ($ais as $user){
                echo $user->name;
                echo '&nbsp&nbsp';
            }

            ?>
        </th>
        <th><?=$modes->email?></th>

        <th><?=date('Y年-m月-d日-h点-i分',$modes->last_login)?></th>
        <th><?=$modes->last_ip?></th>
        <th>
            <?=\yii\bootstrap\Html::a('修改',['user/edit','id'=>$modes->id],['class'=>'btn btn-info btn-xs'])?>
            <?=\yii\bootstrap\Html::a('删除',['user/delete','id'=>$modes->id],['class'=>'btn btn-danger btn-xs'])?>
        </th>
    </tr>
    <?php endforeach;?>
</table>