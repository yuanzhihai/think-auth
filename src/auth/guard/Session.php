<?php
declare ( strict_types = 1 );

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

    protected $session;

    protected $event;

    protected $cookie;

    protected $request;

    public function __construct(StatefulProvider $provider,\think\Session $session,Event $event,Cookie $cookie,Request $request)
    {
        $this->session = $session;
        $this->event   = $event;
        $this->cookie  = $cookie;
        $this->request = $request;
        parent::__construct( $provider );
    }

    protected function retrieveUser()
    {
        $id = $this->session->get( $this->getName() );

        $user = null;

        if (!is_null( $id )) {
            $user = $this->provider->retrieveById( $id );
        }

        $recaller = $this->getRecaller();

        if (is_null( $user ) && !is_null( $recaller )) {
            $user = $this->getUserByRecaller( $recaller );

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

    public function getRecallerName(): string
    {
        return 'remember_'.sha1( static::class );
    }

    protected function getRecaller()
    {
        return $this->request->cookie( $this->getRecallerName() );
    }

    protected function getUserByRecaller($recalled)
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

        if (!is_null( $this->getRecaller() )) {
            $recalled = $this->getRecallerName();
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
        $this->cookie->forever( $this->getRecallerName(),$value );
    }

}
