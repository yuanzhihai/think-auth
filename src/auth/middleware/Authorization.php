<?php

namespace yzh52521\auth\middleware;

use Closure;
use think\Request;
use yzh52521\Auth;
use yzh52521\auth\exception\AuthorizationException;
use yzh52521\auth\interfaces\Authorizable;

/**
 * 权限管理
 * Class Authorization
 *
 * @package think\auth\behavior
 */
class Authorization
{

    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request,Closure $next)
    {

        /** @var Authorizable $user */
        $user = $this->auth->user();

        $rule = $request->rule();

        if ($rule) {
            $roles = $rule->getOption( 'roles',[] );
            if (!empty( $roles ) && !$user->hasRole( $roles )) {
                throw new AuthorizationException();
            }

            $permissions = $rule->getOption( 'permissions',[] );
            $rest        = $rule->getOption( 'rest' );

            if (!empty( $permissions ) && $rest && $this->isAssoc( $permissions )) {
                if (isset( $permissions['*'] ) && !$user->hasPermission( $permissions['*'],true )) {
                    throw new AuthorizationException;
                }
                if (isset( $permissions[$rest] ) && !$user->hasPermission( $permissions[$rest],true )) {
                    throw new AuthorizationException;
                }
            } elseif (!$user->hasPermission( $permissions,true )) {
                throw new AuthorizationException;
            }
        }

        return $next( $request );
    }

    /**
     * 是否为关联数组
     *
     * @param array $param
     * @return bool
     */
    private function isAssoc(array $param): bool
    {
        return array_keys( $param ) !== range( 0,count( $param ) - 1 );
    }
}
