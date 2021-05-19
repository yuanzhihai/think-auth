<?php

return [
    'auth_on'    => 1, // 权限开关
    'auth_type'  => 1, // 认证方式，1为实时认证；2为登录认证。
    'auth_user'  => 'user', // 用户信息不带前缀表名
    'auth_admin' => ['1'],  //超级管理员id
    'allow'      => ['admin/login'] //白名单
];
