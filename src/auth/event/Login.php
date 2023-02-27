<?php

namespace yzh52521\auth\event;


class Login
{

    public function __construct(public $user,public $remember)
    {
    }
}