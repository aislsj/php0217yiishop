<?php
namespace backend\controllers;

use backend\models\Text;
use yii\web\Controller;

class TextController extends Controller{
    public function actionText(){
       $model = new Text();

        return $this->render('index',['model'=>$model]);
    }

    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'maxLength' => 5,
                'minLength' => 5
            ],
        ];
    }
}