<?php
$from = \yii\bootstrap\ActiveForm::begin();
echo $from->field($model,'label');
echo $from->field($model,'url');
echo $from->field($model,'parent_id')->dropDownList(\backend\models\Menu::getMenu(),['prompt'=>'请选择分类']);
echo $from->field($model,'sort');
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
