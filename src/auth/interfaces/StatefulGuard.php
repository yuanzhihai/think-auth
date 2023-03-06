<?php

namespace yzh52521\auth\interfaces;

/**
 * 保持登录状态
 * Interface StatefulGuard
 * @package yzh52521\auth\interfaces
 */
interface StatefulGuard extends Guard
{
    public function attempt($credentials,$remember = false);

    /**
     * 设置登录用户
     *
     * @param mixed $user
     * @param bool $remember
     */
    public function login($user,$remember = false);

    /**
     * 用户是否使用了“记住我”
     *
     * @return bool
     */
    public function viaRemember();

    /**
     * 登出
     *
     * @return void
     */
    public function logout();
}
