<?php

namespace yzh52521\auth\guard;

use think\helper\Str;
use think\Request;
use yzh52521\auth\credentials\TokenCredentials;
use yzh52521\auth\interfaces\Authorizable;
use yzh52521\auth\interfaces\Guard;
use yzh52521\auth\interfaces\Provider;
use yzh52521\auth\traits\GuardHelpers;

class Token implements Guard
{
    use GuardHelpers;


    public function __construct(
        protected Request $request,
        Provider          $provider)
    {
        $this->provider = $provider;
    }

    /**
     * 获取通过认证的用户
     *
     * @return Authorizable|mixed|null
     */
    public function user()
    {
        if (!is_null( $this->user )) {
            return $this->user;
        }

        $user = null;

        $token = $this->getTokenFromRequest();

        if (!empty( $token )) {
            $credentials = new TokenCredentials( $token );
            $user        = $this->provider->retrieveByCredentials( $credentials );
        }

        return $this->user = $user;
    }

    protected function getTokenFromRequest()
    {
        $token = $this->request->param( 'access-token' );
        if (empty( $token )) {
            $header = $this->request->header( 'Authorization' );
            if (!empty( $header )) {
                if (str_starts_with( $header,'Bearer ' )) {
                    $token = Str::substr( $header,7 );
                }
            }
        }

        return $token;
    }

}
