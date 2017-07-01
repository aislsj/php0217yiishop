<?php
namespace backend\widgets;

use backend\models\Menu;
use yii\bootstrap\Widget;
use yii\bootstrap\NavBar;
use yii;
use yii\bootstrap\Nav;

class MenuWidget extends Widget{

    //Widget实例化后执行的代码
    public function init(){
        parent::init();
    }


    //Widget被调用的时候执行的代码
    public function run(){
        NavBar::begin([
            'brandLabel' => '东风电商后台管理系统',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
        $menuItems = [

        ];
        //根据用户的权限显示菜单
//        $menuItems[] =['label' => '所有管理','items'=>[//这是原来的自己设计的样式(下拉框形式)
//            ['label' => '商品管理', 'url' => ['/goods/index'],'items'=>[
//                ]],
//            ['label' => '权限管理', 'url' => ['/rbac/rules-index']],
//            ['label' => '角色管理', 'url' => ['/rbac/role-index']],
//            ['label' => '管理员管理', 'url' => ['/user/index']],
//        ]];
        $menus = Menu::findAll(['parent_id'=>0]);//显示所有菜单
        foreach($menus as $menu){
            $item = ['label'=>$menu->label,'items'=>[]];
            foreach($menu->children as $child){
//                ['label' => '角色管理', 'url' => ['/rbac/role-index']],
                //根据用户权限判断，该菜单是否显示
                if(Yii::$app->user->can($child->url)){
                    $item['items'][] = ['label'=>$child->label,'url'=>[$child->url]];//对应格式
                }
            }
            //如果该一级菜单没有子菜单，就不显示
            if(!empty($item['items'])){
                $menuItems[] = $item;
            }
        }

        if (Yii::$app->user->isGuest) {//这是未登录的状态
//            $menuItems[] = ['label' => '登录', 'url' => ['/user/login']];
            $menuItems[] = ['label' => '登录', 'url' =>Yii::$app->user->loginUrl];
        } else {//登录的状态
            $menuItems[] = ['label' => '退出-('.Yii::$app->user->identity->username.')',
                'url' =>['user/logout']];
        }
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $menuItems,
        ]);
        NavBar::end();
    }






















}