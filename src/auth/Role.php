<?php

namespace yzh52521\auth;

class Role
{
    /**
     * @param string $name   角色名称
     * @param array $permissions 权限列表
     */
    public function __construct(protected string $name,protected  array $permissions = [])
    {
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * 设置角色权限
     * @param $permissions
     */
    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;
    }

    /**
     * 获取权限列表
     * @return array
     */
    public function getPermissions()
    {
        return $this->permissions;
    }
}
