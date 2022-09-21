<?php
declare ( strict_types = 1 );

namespace yzh52521\auth\traits;

use yzh52521\auth\Role;
use yzh52521\facade\Gate;

trait AuthorizableUser
{
    /**
     * 获取用户角色
     * @return array
     */
    public function getRoles(): array
    {
        return [];
    }

    /**
     * 是否具有某个角色
     *
     * @param array|string $name
     * @param bool $requireAll
     * @return bool
     */
    public function hasRole($name,$requireAll = false): bool
    {
        return Gate::forUser( $this )->hasRole( $name,$requireAll );
    }

    /**
     * 获取用户的所有权限
     *
     * @return array
     */
    public function getPermissions(): array
    {
        return Gate::forUser( $this )->getPermissions();
    }

    /**
     * 是否具有某个权限
     *
     * @param      $name
     * @param bool $requireAll
     * @return bool
     */
    public function hasPermission($name,$requireAll = false): bool
    {
        return Gate::forUser( $this )->hasPermission( $name,$requireAll );
    }

    /**
     * 检查权限
     *
     * @param       $ability
     * @param array $args
     * @return bool|mixed
     */
    public function can($ability,...$args): mixed
    {
        return Gate::forUser( $this )->can( $ability,...$args );
    }
}
