<?php
declare ( strict_types = 1 );

namespace yzh52521\auth\middleware;

use Closure;
use yzh52521\Auth;
use yzh52521\auth\exception\AuthenticationException;

/**
 * 用户身份认证
 * Class Authentication
 *
 * @package think\auth\behavior
 */
class Authentication
{

    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request,Closure $next,$guards = null)
    {
        $this->authenticate( (array)$guards );

        return $next( $request );
    }

    protected function authenticate($guards)
    {
        if (empty( $guards )) {
            return $this->auth->authenticate();
        }

        $lastException = null;

        foreach ( $guards as $guard ) {
            try {
                $this->auth->guard( $guard )->authenticate();
                return $this->auth->shouldUse( $guard );
            } catch ( AuthenticationException $e ) {
                $lastException = $e;
            }
        }

        throw $lastException;
    }
}
