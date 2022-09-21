<?php
declare ( strict_types = 1 );

namespace yzh52521\auth\password;

use Closure;
use think\App;
use UnexpectedValueException;
use yzh52521\auth\interfaces\CanResetPassword;
use yzh52521\auth\interfaces\Provider;
use yzh52521\facade\Auth;

class Broker
{

    /** @var Provider */
    protected Provider $provider;

    /**
     * The custom password validator callback.
     *
     * @var \Closure
     */
    protected Closure $passwordValidator;

    /** @var Token */
    protected Token $token;

    /** @var Auth */
    protected Auth $auth;

    public function __construct(App $app,\yzh52521\Auth $auth,Token $token)
    {
        $this->token    = $token;
        $this->provider = $auth->createUserProvider( $app->config->get( 'auth.password.provider' ) );
    }

    /**
     * 发送重置密码链接
     * @param array $credentials
     * @return void
     */
    public function sendResetLink(array $credentials)
    {
        $user = $this->getUser( $credentials );
        if (is_null( $user )) {
            throw new Exception( Exception::INVALID_USER );
        }
        $user->sendPasswordResetNotification( $this->createToken( $user ) );
    }

    /**
     * 重置密码
     *
     * @param array $credentials
     * @param Closure $callback
     */
    public function reset(array $credentials,Closure $callback)
    {
        $user = $this->validateReset( $credentials );

        $pass = $credentials['password'];

        $callback( $user,$pass );

        $this->token->delete( $user );
    }

    protected function createToken(CanResetPassword $user): string
    {
        return $this->token->create( $user );
    }

    protected function validateReset(array $credentials)
    {
        if (is_null( $user = $this->getUser( $credentials ) )) {
            throw new Exception( Exception::INVALID_USER );
        }

        if (!$this->validateNewPassword( $credentials )) {
            throw new Exception( Exception::INVALID_PASSWORD );
        }

        if (!$this->token->exists( $user,$credentials['token'] )) {
            throw new Exception( Exception::INVALID_TOKEN );
        }

        return $user;
    }

    public function validator(Closure $callback): void
    {
        $this->passwordValidator = $callback;
    }

    protected function validateNewPassword(array $credentials): bool
    {
        if (isset( $this->passwordValidator )) {
            [$password,$confirm] = [
                $credentials['password'],
                $credentials['password_confirm'],
            ];

            return call_user_func( $this->passwordValidator,$credentials ) && $password === $confirm;
        }

        return $this->validatePasswordWithDefaults( $credentials );
    }

    protected function validatePasswordWithDefaults(array $credentials): bool
    {
        [$password,$confirm] = [
            $credentials['password'],
            $credentials['password_confirm'],
        ];

        return $password === $confirm && mb_strlen( $password ) >= 6;
    }

    /**
     * @param array $credentials
     * @return mixed|CanResetPassword
     */
    protected function getUser(array $credentials): mixed
    {
        if (isset( $credentials['token'] )) {
            unset( $credentials['token'] );
        }

        $user = $this->provider->retrieveByCredentials( $credentials );

        if ($user && !$user instanceof CanResetPassword) {
            throw new UnexpectedValueException( 'User must implement CanResetPassword interface.' );
        }

        return $user;
    }

    public function tokenExists(array $credentials,$token): bool
    {
        if (!is_null( $user = $this->getUser( $credentials ) )) {
            return $this->token->exists( $user,$token );
        }

        return false;
    }
}
