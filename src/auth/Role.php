<?php
declare ( strict_types = 1 );

namespace yzh52521\auth;

class Role
{
    /** @var array 权限列表 */
    protected array $permissions = [];

    /** @var string 角色名称 */
    protected string $name;

    public function __construct($name,$permissions = [])
    {
        $this->name        = $name;
        $this->permissions = $permissions;
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
