<?php

declare(strict_types=1);

\think\Console::starting(
    function (\think\Console $console) {
        $console->addCommands(
            [
                'auth:config' => '\\think\\auth\\command\\AuthConfig'
            ]
        );
    }
);

if (!function_exists('auth_check')) {
    /**
     * 权限检测快捷方法
     * @param $name string|array  需要验证的规则列表,支持逗号分隔的权限规则或索引数组
     * @param $uid  int           认证用户的id
     * @param int $type 认证类型
     * @param string $mode 执行check的模式
     * @param string $relation 如果为 'or' 表示满足任一条规则即通过验证;如果为 'and'则表示需满足所有规则才能通过验证
     * @return bool 通过验证返回true;失败返回false
     */
    function auth_check($name, $uid, $type = 1, $mode = 'url', $relation = 'or')
    {
        return \think\Auth::check($name, $uid, $type, $mode, $relation);
    }
}

if (!function_exists('get_auth_rules')) {
    /**
     * 返回用户的所有规则表
     * @param int $uid 认证用户的id
     * @param int $type 认证类型
     * @return array
     */
    function get_auth_rules($uid, $type = 1)
    {
        return \think\Auth::rules($uid, $type);
    }
}

if (!function_exists('get_auth_role_ids')) {
    /**
     * 获取用户所有角色 id
     * @param $uid
     * @return mixed
     */
    function get_auth_role_ids($uid)
    {
        return \think\Auth::roles($uid, 'role_id');
    }
}

if (!function_exists('get_auth_roles')) {
    /**
     * 获取用户所有角色数据
     * @param $uid
     * @return mixed
     */
    function get_auth_roles($uid)
    {
        return \think\Auth::roles($uid);
    }
}
