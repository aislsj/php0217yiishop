<?=\yii\bootstrap\Html::a('添加',['article/add'],['class'=>'btn btn-success'])?>
<table class="table table-bordered">
    <tr>
        <th class="col-lg-0.5">ID</th>
        <th class="col-lg-2">文章标题</th>
        <th class="col-lg-3">文章简介</th>
        <th class="col-lg-2">文章分类</th>
        <th class="col-lg-0.5">排序</th>
        <th class="col-lg-0.5">状态</th>
        <th class="col-lg-1">创建时间</th>
        <th class="col-lg-2">操作</th>
    </tr>
    <?php foreach($brand as $brand):?>
        <tr>
            <?php
            if($brand->status >=0 ){
             ?>
                <td><?=$brand->id?></td>
                <td><?=$brand->name?></td>
                <td><?=$brand->intro?></td>
                <td><?=$brand->article_category->name?></td>
                <td><?=$brand->sort?></td>
                <td><?=($brand->status)?'正常':'隐藏'?></td>
                <td><?=date('Y-m-d ',$brand->create_time)?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('查看',['article/show','id'=>$brand->id],['class'=>'btn btn-info btn-xs'])?>
                    <?=\yii\bootstrap\Html::a('修改',['article/edit','id'=>$brand->id],['class'=>'btn btn-info btn-xs'])?>
                    <?=\yii\bootstrap\Html::a('删除',['article/delete','id'=>$brand->id],['class'=>'btn btn-danger btn-xs'])?>
                </td>
            <?php
            }
            ?>

        </tr>
    <?php endforeach;?>
</table>

<?php
//分页工具条
//var_dump($page);exit;
echo \yii\widgets\LinkPager::widget([

    'pagination'=>$page,
    'nextPageLabel'=>'下一页',
    'prevPageLabel'=>'上一页',
]);