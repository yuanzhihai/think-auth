<?php

namespace yzh52521\ThinkAuth;

use yzh52521\ThinkAuth\command\Publish;

class Service extends \think\Service
{

    public function boot()
    {
        $this->commands(['auth:publish' => Publish::class]);
    }
}
