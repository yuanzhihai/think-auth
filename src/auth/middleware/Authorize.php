<?php

namespace yzh52521\auth\middleware;

use Closure;
use yzh52521\Auth;
use yzh52521\auth\exception\AuthorizationException;

class Authorize
{

    public function __construct(protected Auth $auth)
    {
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
