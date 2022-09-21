<?php
declare ( strict_types = 1 );

namespace yzh52521\auth;


use yzh52521\auth\traits\PoliciesCollection;

class Collection extends \think\model\Collection
{
    use PoliciesCollection;
}