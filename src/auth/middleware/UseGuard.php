<?php

namespace yzh52521\auth\middleware;

use Closure;
use yzh52521\Auth;

class UseGuard
{

    public function __construct(protected Auth $auth)
    {
    }

    public function handle($request,Closure $next,$guard)
    {
        $this->auth->shouldUse( $guard );

        return $next( $request );
    }
}
