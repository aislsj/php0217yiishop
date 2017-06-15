<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'intro');
echo $form->field($model2,'content')->textarea();
//var_dump($category['0']['status']);exit;
//var_dump($category['0']['status']);exit;
//if(($model->article_category_id)>=0){
//echo $form->field($model,'article_category_id')->dropDownList(\yii\helpers\ArrayHelper::map($category,'id','name'));
//}

//echo $form->field($model,'article_category_id')->dropDownList(\yii\helpers\ArrayHelper::map($category,'id','name'));


//var_dump($category);exit;
echo $form->field($model,'article_category_id')->dropDownList($category,['prompt'=>'=请选择分类=']);

//echo $form->field($model,'article_category_id');
echo $form->field($model,'sort');
echo $form->field($model,'status')->radioList([1=>'正常',0=>'隐藏']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();