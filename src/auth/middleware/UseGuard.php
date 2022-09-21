<?php

namespace yzh52521\auth\middleware;

use Closure;
use yzh52521\Auth;

class UseGuard
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request,Closure $next,$guard)
    {
        $this->auth->shouldUse( $guard );

        return $next( $request );
    }
}
