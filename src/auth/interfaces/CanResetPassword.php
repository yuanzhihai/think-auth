<?php

namespace yzh52521\auth\interfaces;

interface CanResetPassword
{

    /**
     * 获取邮箱或者手机号码
     * @return mixed
     */
    public function getEmailForResetPassword();

    /**
     * 发送重置密码token通知
     * @param $token
     * @return mixed
     */
    public function sendPasswordResetNotification($token);

}