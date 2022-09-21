<?php
declare ( strict_types = 1 );

namespace yzh52521\auth\traits;

use yzh52521\facade\Auth;

trait AuthUser
{
    public function user()
    {
        return Auth::user();
    }
}
