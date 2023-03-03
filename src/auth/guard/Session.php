<?php

namespace yzh52521\auth\guard;

use think\Cookie;
use think\Event;
use think\helper\Str;
use think\Request;
use yzh52521\auth\credentials\BaseCredentials;
use yzh52521\auth\credentials\PasswordCredential;
use yzh52521\auth\event\Login;
use yzh52521\auth\interfaces\StatefulGuard;
use yzh52521\auth\interfaces\StatefulProvider;

class Session extends Password implements StatefulGuard
{

    /**
     * 是否通过cookie记住用户
     *
     * @var bool
     */
    protected $viaRemember = false;

    protected $tokenRetrievalAttempted = false;

    public function __construct(StatefulProvider $provider,protected \think\Session $session,protected Event $event,protected Cookie $cookie,protected Request $request)
    {
        parent::__construct( $provider );
    }

    protected function retrieveUser()
    {
        $id = $this->session->get( $this->getName() );

        $user = null;

        if (!is_null( $id )) {
            $user = $this->provider->retrieveById( $id );
        }

        $recalled = $this->getRecalled();

        if (is_null( $user ) && !is_null( $recalled )) {
            $user = $this->getUserByRecalled( $recalled );

            if ($user) {
                $this->session->set( $this->getName(),$this->provider->getId( $user ) );

                $this->event->trigger( new Login( $user,true ) );
            }
        }

        return $user;
    }

    /**
     * Session键名
     *
     * @return string
     */
    protected function getName(): string
    {
        return 'login_'.sha1( static::class );
    }

    public function getRecalledName(): string
    {
        return 'remember_'.sha1( static::class );
    }

    protected function getRecalled()
    {
        return $this->request->cookie( $this->getRecalledName() );
    }

    protected function getUserByRecalled($recalled)
    {
        if ($this->validRecalled( $recalled ) && !$this->tokenRetrievalAttempted) {
            $this->tokenRetrievalAttempted = true;

            [$id,$token] = explode( '|',$recalled,2 );

            $this->viaRemember = !is_null( $user = $this->provider->retrieveByToken( $id,$token ) );

            return $user;
        }
    }

    protected function validRecalled($recalled): bool
    {
        if (!is_string( $recalled ) || str_contains( $recalled,'|' )) {
            return false;
        }

        $segments = explode( '|',$recalled );

        return count( $segments ) == 2 && trim( $segments[0] ) !== '' && trim( $segments[1] ) !== '';
    }

    /**
     * 尝试登录
     *
     * @param array $credentials
     * @param bool $remember
     * @return bool
     */
    public function attempt($credentials,$remember = false): bool
    {
        if (!$credentials instanceof BaseCredentials) {
            $credentials = PasswordCredential::fromArray( $credentials );
        }

        if ($this->validate( $credentials )) {
            $this->login( $this->lastValidated,$remember );
            return true;
        }

        return false;
    }

    /**
     * 通过id获取认证用户
     * @param mixed $id
     * @param bool $remember
     * @return false|mixed
     */
    public function loginUsingId($id,$remember = false)
    {
        if (!is_null( $user = $this->provider->retrieveById( $id ) )) {
            $this->login( $user,$remember );

            return $user;
        }

        return false;
    }

    /**
     * 只验证一次 通过用户id
     *
     * @param mixed $id
     * @return mixed|false
     */
    public function onceUsingId($id)
    {
        if (!is_null( $user = $this->provider->retrieveById( $id ) )) {
            $this->setUser( $user );

            return $user;
        }
        return false;
    }

    /**
     * 设置登录用户
     *
     * @param mixed $user
     * @param bool $remember
     * @return void
     */
    public function login($user,$remember = false)
    {
        $this->session->set( $this->getName(),$this->provider->getId( $user ) );

        if ($remember) {
            $this->createRememberTokenIfDoesntExist( $user );
            $this->createRecalled( $user );
        }

        $this->event->trigger( new Login( $user,$remember ) );

        $this->setUser( $user );
    }

    /**
     * 用户是否使用了“记住我”
     *
     * @return bool
     */
    public function viaRemember(): bool
    {
        return $this->viaRemember;
    }

    /**
     * 登出
     *
     * @return void
     */
    public function logout()
    {
        $user = $this->user();

        $this->clearUserDataFromStorage();

        if (!is_null( $this->user )) {
            $this->refreshRememberToken( $user );
        }

        $this->user = null;
    }

    protected function clearUserDataFromStorage()
    {
        $this->session->delete( $this->getName() );

        if (!is_null( $this->getRecalled() )) {
            $recalled = $this->getRecalledName();
            $this->cookie->delete( $recalled );
        }
    }

    protected function createRememberTokenIfDoesntExist($user)
    {
        if (empty( $this->provider->getRememberToken( $user ) )) {
            $this->refreshRememberToken( $user );
        }
    }

    protected function refreshRememberToken($user)
    {
        $this->provider->setRememberToken( $user,Str::random( 60 ) );
    }

    protected function createRecalled($user)
    {
        $value = $this->provider->getId( $user ).'|'.$this->provider->getRememberToken( $user );
        $this->cookie->forever( $this->getRecalledName(),$value );
    }

}
