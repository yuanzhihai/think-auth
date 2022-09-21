<?php

namespace yzh52521\auth\interfaces;

interface PolicyResolver
{
    public function resolvePolicy($class);
}
