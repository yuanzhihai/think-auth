<?php

namespace yzh52521\auth\exception;

class UnauthorizedHttpException extends AuthenticationException
{
    public function __construct(string $challenge,$message = null)
    {
        $headers = [
            'WWW-Authenticate' => $challenge,
        ];

        parent::__construct( $message,$headers );
    }
}
