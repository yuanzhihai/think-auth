<?php

namespace yzh52521\auth\guard;

use yzh52521\auth\interfaces\Guard;
use yzh52521\auth\interfaces\Provider;
use yzh52521\auth\traits\GuardHelpers;

abstract class Password implements Guard
{
    use GuardHelpers;

    public function __construct(Provider $provider)
    {
        $this->provider = $provider;
    }
}
