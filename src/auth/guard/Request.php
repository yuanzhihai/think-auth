<?php

namespace yzh52521\auth\guard;

use yzh52521\auth\credentials\RequestCredentials;
use yzh52521\auth\interfaces\Guard;
use yzh52521\auth\interfaces\Provider;
use yzh52521\auth\traits\GuardHelpers;

class Request implements Guard
{
    use GuardHelpers;

    protected $callback;

    public function __construct(Provider $provider,protected \think\Request $request)
    {
        $this->provider = $provider;
    }

    protected function retrieveUser()
    {
        $credentials = new RequestCredentials( $this->request );
        return $this->provider->retrieveByCredentials( $credentials );
    }

}
