<?php

namespace yzh52521\auth\guard;

use think\helper\Str;
use think\Request;
use yzh52521\auth\credentials\PasswordCredential;
use yzh52521\auth\exception\UnauthorizedHttpException;
use yzh52521\auth\interfaces\Provider;

class Basic extends Password
{

    public function __construct(Provider $provider,protected Request $request)
    {
        parent::__construct( $provider );
    }

    public function authenticate()
    {
        if (!$this->check()) {
            throw new UnauthorizedHttpException( 'Basic','Invalid credentials.' );
        }
    }

    protected function retrieveUser()
    {
        $credentials = $this->getCredentialsFromRequest();

        if (!empty( $credentials )) {
            return $this->provider->retrieveByCredentials( $credentials );
        }

        return null;
    }

    protected function getCredentialsFromRequest()
    {
        $header = $this->request->header( 'Authorization' );

        if (!empty( $header )) {
            if (str_starts_with( $header,'Basic ' )) {
                $token   = Str::substr( $header,6 );
                $decoded = base64_decode( $token );
                [$username,$password] = explode( ':',$decoded );

                return new PasswordCredential( $username,$password );
            }
        }

        return false;
    }
}
