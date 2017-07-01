<?=\yii\bootstrap\Html::a('添加',['article_category/add'],['class'=>'btn btn-success'])?>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>文章名称</th>
        <th>内容</th>
        <th>排序</th>
        <th>状态</th>
        <th>文章类型</th>
        <th>操作</th>
    </tr>
    <?php foreach($models as $brand):?>
        <tr>
            <?php
            if($brand->status >=0 ){
                ?>
                <td><?=$brand->id?></td>
                <td><?=$brand->name?></td>
                <td><?=$brand->intro?></td>
                <td><?=$brand->sort?></td>
                <td><?=($brand->status)?'正常':'隐藏'?></td>
                <td><?=($brand->is_help)?'导购文章':'帮助文章'?></td>
                <td>
                   <?php
                   if(\Yii::$app->user->can('article_category/edit'))
                       echo \yii\bootstrap\Html::a('修改',['article_category/edit','id'=>$brand->id],['class'=>'btn btn-info btn-xs']);
                       ?>
                   <?php
                   if(\Yii::$app->user->can('article_category/delete'))
                       echo \yii\bootstrap\Html::a('删除',['article_category/delete','id'=>$brand->id],['class'=>'btn btn-danger btn-xs']);
                   ?>
                </td>
                <?php
            }
            ?>
        </tr>
    <?php endforeach;?>
</table>