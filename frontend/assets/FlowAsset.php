<?php
namespace frontend\assets;

use yii\web\AssetBundle;

class FlowAsset extends AssetBundle
{

//<link rel="stylesheet" href="style/base.css" type="text/css">
//<link rel="stylesheet" href="style/global.css" type="text/css">
//<link rel="stylesheet" href="style/header.css" type="text/css">
//<link rel="stylesheet" href="style/fillin.css" type="text/css">
//<link rel="stylesheet" href="style/footer.css" type="text/css">
    public $basePath = '@webroot';//静态资源的硬盘路径
    public $baseUrl = '@web';//静态资源的url路径
    public $css = [
        'style/base.css',
        'style/global.css',
        'style/header.css',
        'style/footer.css',
        'style/cart.css',
        'style/fillin.css',
        'style/success.css',
    ];
    //需要加载的js文件
    public $js = [
        'js/cart1.js',
        'js/cart2.js'
    ];
    //和其他静态资源管理器的依赖关系
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}