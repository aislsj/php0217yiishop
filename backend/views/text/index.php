<?php
$form =\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');

echo $form->field($model,'code',['options'=>['class'=>'checkcode']])
    ->widget(\yii\captcha\Captcha::className(),['captchaAction'=>'text/captcha','template'=>'{input}{image}']);

echo \yii\bootstrap\Html::button('提交测试',['class'=>'btn btn-info btn-xs']);
\yii\bootstrap\ActiveForm::end();