<?php

use yzh52521\facade\Gate;

/**
 * @param mixed $user
 * @param string $ability
 * @param array $args
 * @return bool
 */
function can($user,$ability,...$args)
{
    return Gate::forUser( $user )->can( $ability,...$args );
}
