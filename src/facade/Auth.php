<?php

namespace yzh52521\facade;

use think\Facade;

/**
 * Class Auth
 *
 * @package yzh52521\facade
 * @mixin \yzh52521\Auth
 */
class Auth extends Facade
{
    protected static function getFacadeClass()
    {
        return 'auth';
    }
}