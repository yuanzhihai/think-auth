<?php
//think-auth 配置文件

use yzh52521\auth\controller\ForgotPasswordController;
use yzh52521\auth\controller\LoginController;
use yzh52521\auth\controller\RegisterController;
use yzh52521\auth\controller\ResetPasswordController;
use yzh52521\auth\model\User;

return [
    'default'          => 'web',
    'guards'           => [
        'web' => [
            'driver'   => 'session',
            'provider' => 'user',
        ],
        'api' => [
            'driver'   => 'token',
            'provider' => 'user',
        ],
    ],
    'providers'        => [
        'user' => [
            'driver' => 'model',
            'model'  => User::class,
        ],
    ],
    'password'         => [
        'provider' => 'user',
    ],
    //设为false,则不注册路由
    'route'            => [
        'group'       => 'auth',
        'controllers' => [
            'login'    => LoginController::class,
            'register' => RegisterController::class,
            'forgot'   => ForgotPasswordController::class,
            'reset'    => ResetPasswordController::class,
        ],
    ],
    'policy_namespace' => '\\app\\policy\\',
    'policies'         => [],
];
