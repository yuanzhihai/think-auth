<?php

namespace yzh52521\auth\notification;

use think\queue\ShouldQueue;
use yzh52521\Notification;
use yzh52521\notification\message\Mail;
use yzh52521\notification\Notifiable;

class ResetPassword extends Notification implements ShouldQueue
{

    public function __construct(public $email,public $token)
    {
    }

    /**
     * 发送渠道
     * @param Notifiable $notifiable
     * @return array
     */
    public function channels($notifiable): array
    {
        return ['mail'];
    }

    /**
     * @param $notifiable
     * @return Mail
     */
    public function toMail($notifiable)
    {
        return ( new Mail() )
            ->subject( '找回密码' )
            ->line( '您收到此电子邮件，是因为我们收到了您的帐户的密码重置请求。' )
            ->action( '重置密码',url( 'AUTH_PASSWORD',['email' => $this->email,'token' => $this->token],true,true ) )
            ->to( $this->email )
            ->line( '如果您没有请求密码重置，则忽略此邮件。' );
    }
}
