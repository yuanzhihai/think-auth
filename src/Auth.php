<?php

declare(strict_types=1);

namespace yzh52521\ThinkAuth;

/**
 * auth 权限检测入口
 * Class Auth
 * @package think
 * @method static check($name, $uid, $type = 1, $mode = 'url', $relation = 'or')
 * @method static rules($uid, $type = 1)
 * @method static roles($uid)
 * @method static allRoles($uid)
 */
class Auth
{
    /**
     * 静态魔术方法
     * @param $method
     * @param $args
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        $model = new \yzh52521\ThinkAuth\service\Auth(app());

        return call_user_func_array([$model, $method], $args);
    }
}
