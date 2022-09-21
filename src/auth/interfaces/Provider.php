<?php
declare ( strict_types = 1 );

namespace yzh52521\auth\interfaces;

interface Provider
{
    /**
     * 根据用户输入的数据获取用户
     * @param mixed $credentials
     * @return mixed
     */
    public function retrieveByCredentials($credentials);
}