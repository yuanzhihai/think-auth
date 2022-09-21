<?php

namespace yzh52521\auth\traits;

use yzh52521\facade\Auth;

trait AuthUser
{
    public function user()
    {
        return Auth::user();
    }
}
