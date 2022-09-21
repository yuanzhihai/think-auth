<?php
declare ( strict_types = 1 );

namespace yzh52521\auth\interfaces;

use yzh52521\auth\Role;

interface Authorizable
{
    /**
     * 获取用户角色
     * @return Role[]
     */
    public function getRoles();

    /**
     * 是否具有某个角色
     * @param array|string $name
     * @param bool $requireAll
     * @return bool
     */
    public function hasRole($name,$requireAll = false);

    /**
     * 获取用户的所有权限
     * @return array
     */
    public function getPermissions();

    /**
     * 是否具有某个权限
     * @param      $name
     * @param bool $requireAll
     * @return bool
     */
    public function hasPermission($name,$requireAll = false);

    /**
     * 检查权限
     * @param       $action
     * @param array $args
     * @return bool|mixed
     */
    public function can($action,...$args);

}