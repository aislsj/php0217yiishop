<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username');
echo $form->field($model,'password')->passwordInput();
//echo $form->field($model,'ais')->radioList([1=>'自动登录',0=>'否']);
echo $form->field($model,'ais')->checkbox();
echo $form->field($model,'code')->widget(\yii\captcha\Captcha::className(),[
   'captchaAction'=>'user/captcha',
    'template'=>'<div class="row"><div class="col-lg-2">{image}</div><div class="col-lg-2">{input}
</div> </div>'
]);
echo \yii\bootstrap\Html::submitButton('登录',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();