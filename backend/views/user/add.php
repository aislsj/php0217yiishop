<?php
$form = \yii\bootstrap\ActiveForm::begin();

echo $form->field($model,'username');
echo $form->field($model,'password_hash')->passwordInput();
echo $form->field($model,'email');
echo $form->field($model,'status')->radioList([1=>'启用',0=>'禁用']);
echo $form->field($model,'permissions')->checkboxList(\backend\models\User::getPermissionOptions());
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info ']);
\yii\bootstrap\ActiveForm::end();
