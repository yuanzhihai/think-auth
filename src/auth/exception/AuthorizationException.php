<?php

namespace yzh52521\auth\exception;

use think\exception\HttpException;

class AuthorizationException extends HttpException
{
    public function __construct($message = 'Forbidden')
    {
        parent::__construct( 403,$message );
    }
}
