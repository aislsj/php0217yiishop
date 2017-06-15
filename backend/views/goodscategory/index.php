<table class="cate table table-bordered">
    <tr>
        <th>ID</th>
        <th>分类名称</th>
        <th>操作</th>
    </tr>
    <?php foreach($models as $model):?>

    <tr data-lft="<?=$model->lft?>" data-rgt="<?=$model->rgt?>" data-tree="<?=$model->tree?>">
        <td><?=$model->id?></td>
        <td><?=str_repeat('  --  ',$model->depth),$model->name?>
            <span class="ais glyphicon glyphicon-chevron-down" style="float: right"></span>
        </td>
        <td>删除
        <?=\yii\bootstrap\Html::a('修改',['goodscategory/edit','id'=>$model->id],['class'=>'btn btn-info btn-xs'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<?php
$js=<<<JS
    $('.ais').click(function(){
        var tr = $(this).closest('tr');
        var tree =parseInt(tr.attr('data-tree'));
        var lft = parseInt(tr.attr('data-lft'));
        var rgt = parseInt(tr.attr('data-rgt'));
        //显示还是隐藏
      var show = $(this).hasClass('glyphicon-chevron-up');
        //切换图片
        $(this).toggleClass('glyphicon-chevron-up');
        $(this).toggleClass('glyphicon-chevron-down');
        $('.cate tr').each(function(){
          if(parseInt($(this).attr('data-tree'))==tree && parseInt($(this).attr('data-lft'))>lft && parseInt($(this).attr('data-rgt'))<rgt){
                  //show?$(this).show():$(this).hide();
                  show?$(this).fadeIn():$(this).fadeOut();
          }
        });
    });
JS;
    $this->registerJs($js);
