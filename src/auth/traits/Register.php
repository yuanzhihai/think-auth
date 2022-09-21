<?php
declare ( strict_types = 1 );

namespace yzh52521\auth\traits;

use think\exception\ValidateException;
use think\Request;
use think\Response;
use think\response\View;
use think\Validate;
use yzh52521\facade\Auth;

trait Register
{

    /**
     * 注册页面
     *
     * @return \think\response\Redirect|View
     */
    public function showRegisterForm()
    {
        if ($this->guard()->user()) {
            return redirect( $this->redirectPath() );
        }

        return view( 'auth/register' );
    }

    public function register(Request $request)
    {
        $this->validate( $request );
        $user = $this->create( $request );
        $this->guard()->login( $user );
        return $this->registered( $user )
            ?: redirect( $this->redirectPath() );
    }

    /**
     * 注册成功后的跳转地址
     *
     * @return string
     */
    protected function redirectPath()
    {
        return '/';
    }

    /**
     * @param mixed $user
     * @return Response|null
     */
    protected function registered($user)
    {
        return null;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    protected function create(Request $request)
    {

    }

    /**
     * 生成验证器
     *
     * @param Request $request
     * @return Validate
     */
    protected function validator(Request $request)
    {
        return ( new Validate() )->batch( true );
    }

    /**
     * 验证
     *
     * @param Request $request
     */
    protected function validate(Request $request)
    {
        $validator = $this->validator( $request );

        if (!$validator->check( $request->param() )) {
            throw new ValidateException( $validator->getError() );
        }
    }

    protected function guard()
    {
        return Auth::guard();
    }
}
