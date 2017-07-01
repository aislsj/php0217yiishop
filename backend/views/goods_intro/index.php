<table class="table table-bordered">
    <tr>
        <th>商品详细</th>
        <th>操作</th>
    </tr>

    <tr>
        <td><?=$model->content?></td>

        <td>
            <?=\yii\bootstrap\Html::a('返回上一级',['goods/index'],['class'=>'btn btn-success btn-xs'])?>
            <?php
            if(\Yii::$app->user->can('goods/del')){
                echo \yii\bootstrap\Html::a('删除',['goods/del'],['class'=>'btn btn-danger btn-xs']);
            }
            ?>
            </td>
    </tr>
</table>