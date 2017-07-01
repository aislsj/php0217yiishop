<?php

namespace backend\controllers;

use backend\components\RbacFilter;
use backend\models\Brand;
use yii\web\UploadedFile;
use yii\data\Pagination;
use xj\uploadify\UploadAction;
use crazyfd\qiniu\Qiniu;


class BrandController extends BackendController

{

    //使用过滤器
    public function behaviors(){
        return [
          'rbac'=>[
              'class'=>RbacFilter::className(),
          ]
        ];
    }

    //添加品牌
    public function actionAdd(){
    $model = new Brand();
    if($model->load(\yii::$app->request->post())){
//        $model->imgFile=UploadedFile::getInstance($model,'imgFile');
        if($model->validate()){
//            if($model->imgFile){
//                $filename = '/images/brand/'.uniqid().'.'.$model->imgFile->extension;
//                $model->imgFile->saveAs(\Yii::getAlias('@webroot').$filename,false);
//                $model->logo = $filename;
//            }
            $model->save();
            \Yii::$app->session->setFlash('success','品牌添加成功');
            return $this->redirect(['brand/index']);
        }
    }
    return $this->render('add',['model'=>$model]);
}
    //修改品牌
    public function actionEdit($id){
        $model = Brand::findOne(['id'=>$id]);
        if($model->load(\yii::$app->request->post())){
            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
            if($model->validate()){
                if($model->imgFile){
                    $filename = '/images/brand/'.uniqid().'.'.$model->imgFile->extension;
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$filename,false);
                    $model->logo = $filename;
                }
                $model->save();
                \Yii::$app->session->setFlash('success','品牌修改成功');
                return $this->redirect(['brand/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //删除品牌
    public function actionDelete($id){
        $models = brand::findOne(['id'=>$id]);
        $models->status=-1;
        $models->save();
        return $this->redirect(['brand/index']);
    }
    //显示品牌列表
    public function actionIndex(){
        //获得所有书籍
        $model = Brand::find();
//        var_dump($model);exit;
        //获取总条数
        $total = $model->count();
//        var_dump($total);exit;
        $page = new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>2,
        ]);
        $Brand = $model->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['brand'=>$Brand,'page'=>$page]);
    }
    //拓展,上传图片
    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
//                'format' => function (UploadAction $action) {
//                    $fileext = $action->uploadfile->getExtension();
//                    $filename = sha1_file($action->uploadfile->tempName);
//                    return "{$filename}.{$fileext}";
//                },
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png','jpeg','gif'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {},
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    $imgUrl = $action->getWebUrl();
                    $ak = '2X9_0_WY_NGmk39Q_VdmcCPAfpTp5d1kTVQLsaBm';
                    $sk = 'wMfIbgxTRUDWdCCyc4ORcrrUgKvr0pqRniNaAQ9k';
                    $domain = 'http://or9s4y31r.bkt.clouddn.com';
                    $bucket = 'php0217';
                    $qiniu = new Qiniu($ak, $sk,$domain, $bucket);
                    $fileName =\Yii::getAlias('@webroot').$imgUrl;
                    $key =  $imgUrl;
                    $re=$qiniu->uploadFile($fileName,$key);
//                  var_dump($re);
                    //从千牛云获取img地址
                    $url = $qiniu->getLink($key);
                    //把地址输出到页面
                    $action->output['fileUrl']=$url;
                },
            ],
        ];
    }

    public function actionTest(){
        $ak = '2X9_0_WY_NGmk39Q_VdmcCPAfpTp5d1kTVQLsaBm';
        $sk = 'wMfIbgxTRUDWdCCyc4ORcrrUgKvr0pqRniNaAQ9k';
        $domain = 'http://or9s4y31r.bkt.clouddn.com.com/';
        $bucket = 'php0217';

        $qiniu = new Qiniu($ak, $sk,$domain, $bucket);
        $fileName = \Yii::getAlias('@webroot').'/upload/test.jpg';
        $key = 'test.jpg';
        $re=$qiniu->uploadFile($fileName,$key);
        var_dump($re);
        $url = $qiniu->getLink($key);
//
//        $qiniu = \Yii::$app->qiniu;
//                    $qiniu->uploadFile(\Yii::getAlias('@webroot').'/upload/test.jpg');
//                $key = 'test.jpg';
//                    //获取该图片在七牛云的地址
//                    $url = $qiniu->getLink();
    }
}
