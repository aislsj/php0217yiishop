<?php

namespace frontend\controllers;

class FlowController extends \yii\web\Controller
{

    public $layout = 'flow';

    public function actionFlow1()
    {
        return $this->render('flow1');
    }

    public function actionFlow2()
    {
        return $this->render('flow2');
    }

    public function actionFlow3()
    {
        return $this->render('flow3');
    }
}
