<?php
declare ( strict_types = 1 );

namespace yzh52521\auth\traits;

use think\exception\ValidateException;
use think\Request;
use think\Response;
use think\Validate;
use yzh52521\auth\password\Broker;
use yzh52521\auth\password\Exception;

trait SendPasswordResetEmail
{
    public function showSendPasswordResetEmailForm()
    {
        return view( 'auth/password/email' );
    }

    public function sendResetLinkEmail(Request $request,Broker $broker)
    {
        $this->validate( $request );

        try {
            $broker->sendResetLink( $request->only( ['email'] ) );
        } catch ( Exception $e ) {
            throw new ValidateException( ['email' => $this->getExceptionMessage( $e->getMessage() )] );
        }

        return $this->sended()
            ?: redirect( $this->redirectPath() );
    }

    /**
     * 发送后的跳转地址
     *
     * @return string
     */
    protected function redirectPath()
    {
        return '/';
    }

    /**
     * @return Response
     */
    protected function sended()
    {

    }

    protected function getExceptionMessage($message)
    {
        switch ( $message ) {
            case Exception::INVALID_USER:
                return '用户不存在';
            case Exception::INVALID_TOKEN:
                return '令牌错误或已过期';
            case Exception::INVALID_PASSWORD:
                return '两次输入的密码不一样';
        }
    }

    protected function validate(Request $request)
    {
        $validator = $this->validator( $request );

        if (!$validator->check( $request->param() )) {
            throw new ValidateException( $validator->getError() );
        }
    }

    /**
     * 生成验证器
     *
     * @param Request $request
     * @return Validate
     */
    protected function validator(Request $request)
    {
        return ( new Validate )->rule( [
            'email' => 'require|email',
        ] )->batch( true );
    }
}
