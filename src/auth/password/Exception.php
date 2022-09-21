<?php
declare ( strict_types = 1 );

namespace yzh52521\auth\password;

class Exception extends \InvalidArgumentException
{
    const INVALID_USER = 'passwords.user';

    const INVALID_PASSWORD = 'passwords.password';

    const INVALID_TOKEN = 'passwords.token';

    public function __construct($message)
    {
        parent::__construct( $message );
    }

}