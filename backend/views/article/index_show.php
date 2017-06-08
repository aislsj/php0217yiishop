<table class="table table-bordered">
    <tr>
        <th class="col-lg-9">内容</th>
        <th class="col-lg-3">操作</th>
    </tr>
    <tr>
        <td><?=$model2->content?></td>
        <td>
            <?=\yii\bootstrap\Html::a('返回上一级',['article/index'],['class'=>'btn btn-success btn-xs']) ?>
            <?=\yii\bootstrap\Html::a('删除',['article/delete','id'=>$model->id],['class'=>'btn btn-danger btn-xs'])?>
        </td>
    </tr>
</table>
