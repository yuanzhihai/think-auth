<?php
declare ( strict_types = 1 );

namespace yzh52521\auth\middleware;

use Closure;
use yzh52521\Auth;
use yzh52521\auth\exception\AuthorizationException;

class Authorize
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request,Closure $next,$ability,...$args)
    {
        $user = $this->auth->user();

        if (!can( $user,$ability,...$args )) {
            throw new AuthorizationException;
        }

        return $next( $request );
    }
}
