<?php
declare ( strict_types = 1 );

namespace yzh52521\auth\interfaces;

interface PolicyResolver
{
    public function resolvePolicy($class);
}
