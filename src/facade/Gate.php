<?php

namespace yzh52521\facade;

use think\Facade;

/**
 * Class Gate
 *
 * @package yzh52521\facade
 * @mixin \yzh52521\auth\Gate
 */
class Gate extends Facade
{
    protected static function getFacadeClass()
    {
        return 'gate';
    }
}