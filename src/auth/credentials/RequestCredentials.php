<?php
declare ( strict_types = 1 );

namespace yzh52521\auth\credentials;

use think\Request;

class RequestCredentials extends BaseCredentials
{
    public function __construct(Request $request)
    {
        parent::__construct( ['request' => $request] );
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->offsetGet( 'request' );
    }
}
