<?php

declare(strict_types=1);

namespace yzh52521\ThinkAuth\model;

use think\Model;

/**
 * 权限组与用户关系
 * Class RoleUser
 * @package think\auth\model
 */
class RoleUser extends Model
{
    // 表名
    protected $name = "auth_role_user";
    /**
     * 数据表主键 复合主键使用数组定义
     * @var string|array
     */
    protected $pk = 'role_id';

    /**
     * 用户角色列表
     * @return \think\model\relation\HasMany
     */
    public function rules(): \think\model\relation\HasMany
    {
        return $this->hasMany(RoleRule::class, 'role_id', 'role_id');
    }

    /**
     * 关联角色
     * @return \think\model\relation\HasOne
     */
    public function role(): \think\model\relation\HasOne
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
}
