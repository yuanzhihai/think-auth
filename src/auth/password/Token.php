<?php
declare ( strict_types = 1 );

namespace yzh52521\auth\password;

use think\Cache;
use think\helper\Str;
use yzh52521\auth\interfaces\CanResetPassword;

class Token
{

    protected Cache $cache;

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    public function create(CanResetPassword $user): string
    {
        $email = $user->getEmailForResetPassword();

        $token = $this->createNewToken();

        $this->cache->set( $this->getCacheKey( $user ),$this->getPayload( $email,$token ) );

        return $token;
    }

    protected function getPayload($email,$token): array
    {
        return ['email' => $email,'token' => $token,'create_time' => time()];
    }

    protected function getCacheKey(CanResetPassword $user): string
    {
        return 'password:reset:'.md5( $user->getEmailForResetPassword() );
    }

    protected function createNewToken(): string
    {
        return sha1( Str::random( 40 ) );
    }

    public function exists(CanResetPassword $user,$token): bool
    {
        $tokenCache = $this->cache->get( $this->getCacheKey( $user ) );

        return $tokenCache && $tokenCache['token'] == $token && $tokenCache['create_time'] + 30 * 60 > time();
    }

    public function delete(CanResetPassword $user)
    {
        $this->cache->delete( $this->getCacheKey( $user ) );
    }

}
