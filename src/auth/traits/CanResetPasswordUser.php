<?php
declare ( strict_types = 1 );

namespace yzh52521\auth\traits;

use yzh52521\auth\notification\ResetPassword;
use yzh52521\notification\Notifiable;

/**
 * Class CanResetPasswordUser
 * @package yzh52521\auth\traits
 * @mixin Notifiable
 */
trait CanResetPasswordUser
{

    /**
     * 获取邮箱或者手机号码
     * @return mixed
     */
    public function getEmailForResetPassword()
    {
        return $this->getAttr( 'email' );
    }

    /**
     * 发送重置密码token通知
     * @param $token
     * @return mixed
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify( new ResetPassword( $this->getAttr( 'email' ),$token ) );
    }
}