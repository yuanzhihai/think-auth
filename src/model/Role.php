<?php

declare(strict_types=1);

namespace yzh52521\ThinkAuth\model;

use think\Model;
use think\facade\Db;

/**
 * 权限角色
 * Class Role
 * @package think\auth\model
 */
class Role extends Model
{
    // 表名
    protected $name = "auth_role";

    /**
     * 删除角色时同时删除与规则，用户的关系数据
     * @param \think\Model $user
     * @throws \Exception
     */
    public static function onAfterDelete($role)
    {
        RoleRule::where(['role_id' => $role->id])->delete();
        RoleUser::where('role_id', $role->id)->delete();
    }

    /**
     * 标准化状态值
     * @param $val
     * @return int
     */
    protected function setStatusAttr($val): int
    {
        switch ($val) {
            case 'on':
            case 'true':
            case '1':
            case 1:
                $val = 1;
                break;
            default:
                $val = 0;
        }
        return $val;
    }

    /**
     * 用户数
     * @return float|int|string
     * @throws \think\Exception
     */
    protected function getUserNumAttr()
    {
        $role_id = $this->getData('id');
        return RoleUser::where(['role_id' => $role_id])->count();
    }

    /**
     * 角色对应权限规则
     * @return \think\model\relation\HasMany
     */
    public function rules(): \think\model\relation\HasMany
    {
        return $this->hasMany('RoleRule', 'role_id', 'id');
    }
}
