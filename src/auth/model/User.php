<?php
declare ( strict_types = 1 );

namespace yzh52521\auth\model;

use think\Model;
use yzh52521\auth\interfaces\Authorizable;
use yzh52521\auth\interfaces\CanResetPassword;
use yzh52521\auth\traits\AuthorizableUser;
use yzh52521\auth\traits\CanResetPasswordUser;

/**
 * 默认用户模型
 * Class User
 * @package think\auth\model
 */
class User extends Model implements Authorizable,CanResetPassword
{
    use AuthorizableUser,CanResetPasswordUser;
}
