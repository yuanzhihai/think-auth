<?php
declare ( strict_types = 1 );

namespace yzh52521\auth\interfaces;

use yzh52521\auth\credentials\BaseCredentials;

interface Guard
{
    /**
     * 认证用户
     * @return mixed
     */
    public function authenticate();

    /**
     * 是否通过认证
     *
     * @return bool
     */
    public function check();

    /**
     * 获取通过认证的用户
     *
     * @return mixed
     */
    public function user();

    /**
     * 设置当前用户
     *
     * @param  $user
     * @return $this
     */
    public function setUser($user);

    /**
     * Validate a user's credentials.
     *
     * @param BaseCredentials $credentials
     * @return bool
     */
    public function validate($credentials);

}
