<?php

namespace backend\controllers;

use backend\models\GoodsIntro;

class Goods_introController extends BackendController
{
    public function actionIndex($id)
    {

        $modle = GoodsIntro::findOne(['goods_id'=>$id]);

        return $this->render('index',['model'=>$modle]);
    }

}
