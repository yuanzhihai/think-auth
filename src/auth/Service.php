<?php

namespace yzh52521\auth;

use think\Route;
use yzh52521\Auth;

class Service extends \think\Service
{
    public function boot()
    {
        $routes = $this->app->config->get( 'auth.route' );
        if ($routes) {
            $this->registerRoutes( function (Route $route) use ($routes) {

                $controllers = $routes['controllers'];

                $route->group( $routes['group'],function () use ($route,$controllers) {
                    //登录
                    $route->get( "login",$controllers['login']."@showLoginForm" );
                    $route->post( "login",$controllers['login']."@login" );
                    $route->get( "logout",$controllers['login']."@logout" );
                    //注册
                    $route->get( 'register',$controllers['register']."@showRegisterForm" );
                    $route->post( "register",$controllers['register']."@register" );
                    //忘记密码
                    $route->get( 'password/forgot',$controllers['forgot']."@showSendPasswordResetEmailForm" );
                    $route->post( "password/forgot",$controllers['forgot']."@sendResetLinkEmail" );
                    //重设密码
                    $route->get( 'password/reset',$controllers['reset']."@showResetForm" )->name( 'AUTH_PASSWORD' );
                    $route->post( "password/reset",$controllers['reset']."@reset" );
                } );
            } );
        }
    }

    public function register()
    {
        $this->app->bind( 'auth',Auth::class );
    }
}
