<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'language'=>'zh-CN',//����
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'backend\models\User',
            'enableAutoLogin' => true,//自动登录保存
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            'loginUrl'=> ['user/login'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'qiniu'=>[
            'class'=>\backend\components\Qiniu::className(),
            'up_host'=>'http//up-z2.qiniu.com',
            'accessKey'=>'2X9_0_WY_NGmk39Q_VdmcCPAfpTp5d1kTVQLsaBm',
            'secretKey'=>'wMfIbgxTRUDWdCCyc4ORcrrUgKvr0pqRniNaAQ9k',
            'bucket'=>'php0217',
            'domain'=>'http://or9s4y31r.bkt.clouddn.com.com/',
        ]
    ],
    'params' => $params,
];
