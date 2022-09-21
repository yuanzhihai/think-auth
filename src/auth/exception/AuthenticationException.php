<?php

namespace yzh52521\auth\exception;

use think\exception\HttpException;

class AuthenticationException extends HttpException
{
    public function __construct($message = 'Unauthorized',array $headers = [])
    {
        parent::__construct( 401,$message,null,$headers );
    }
}
