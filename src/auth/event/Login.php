<?php
declare ( strict_types = 1 );

namespace yzh52521\auth\event;


class Login
{

    public $user;

    public $remember;

    public function __construct($user,$remember)
    {
        $this->user     = $user;
        $this->remember = $remember;
    }
}