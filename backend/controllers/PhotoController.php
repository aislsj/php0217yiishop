<?php

namespace backend\controllers;

use backend\models\Goods;
use backend\models\Photo;
use yii\web\Controller;
use yii\web\UploadedFile;
use xj\uploadify\UploadAction;

class PhotoController extends Controller
{
    public function actionIndex($id)
    {

        $model = Photo::findAll(['good_id'=> $id]);
//        var_dump($model);exit;

        return $this->render('index',['model'=>$model,'model2'=>$id]);
    }


    public function actionEdit($id){
        $model = Photo::findOne($id);
//        var_dump($model);
        $model->imgFile=UploadedFile::getInstance($model,'imgFile');
        if($model->load(\yii::$app->request->post())){
            if($model->validate()){
//                var_dump($model->imgFile);exit;
                if ($model->imgFile) {
                    $filename = '/images/photos/' . uniqid() . '.' . $model->imgFile->extension;
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot') . $filename, false);
                    $model->url = $filename;
                }
            }


            $model->save();
            \Yii::$app->session->setFlash('success','商品添加成功');
            return $this->redirect(['photo/index','id'=>$model->good_id]);
        }
        return $this->render('add',['model'=>$model]);
    }


    public function actions() {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => "",//图片访问路径前缀
                    "imagePathFormat" => "/upload/{yyyy}{mm}{dd}/{time}{rand:6}" ,//上传保存路径
                    "imageRoot" => \Yii::getAlias("@webroot"),
                ],
            ],

            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload/logo',
                'baseUrl' => '@web/upload/logo',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                //'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                /*'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },*/
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "/{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png','gif'],
                    'maxSize' => 3 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    //图片上传成功的同时，将图片和商品关联起来
                    $model = new Photo();
                    $model->good_id = \Yii::$app->request->post('good_id');
                    $model->url = $action->getWebUrl();
                    $model->save();
                    $action->output['fileUrl'] = $model->url;
                    //$action->output['goods_id'] = $model->goods_id;

//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                    //$action->output['Path'] = $action->getSavePath();
                    /*
                     * 将图片上传到七牛云
                     */
                    /* $qiniu = \Yii::$app->qiniu;//实例化七牛云组件
                     $qiniu->uploadFile($action->getSavePath(),$action->getFilename());//将本地图片上传到七牛云
                     $url = $qiniu->getLink($action->getFilename());//获取图片在七牛云上的url地址
                     $action->output['fileUrl'] = $url;//将七牛云图片地址返回给前端js
                    */
                },
            ],
        ];
    }


    /*
  * 商品相册
  */
    public function actionGallery($id)
    {
//        $goods = Photo::find()->where(['goods_id'=>$id]);
        $goods = Goods::findOne(['id'=>$id]);
//        var_dump($goods);exit;
        if($goods == null){
            throw new NotFoundHttpException('商品不存在');
        }
        return $this->render('add',['goods'=>$goods]);

    }

    /*
     * AJAX删除图片
     */
    public function actionDelGallery(){
        $id = \Yii::$app->request->post('id');
        $model = Photo::findOne(['id'=>$id]);
        if($model && $model->delete()){
            return 'success';
        }else{
            return 'fail';
        }

    }
}
