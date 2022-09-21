<?php
declare ( strict_types = 1 );

namespace yzh52521\auth\controller;

use yzh52521\auth\traits\SendPasswordResetEmail;

class ForgotPasswordController
{
    use SendPasswordResetEmail;
}