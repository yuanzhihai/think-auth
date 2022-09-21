<?php
declare ( strict_types = 1 );

namespace yzh52521;

use InvalidArgumentException;
use think\helper\Arr;
use think\helper\Str;
use think\Manager;
use yzh52521\auth\guard\Session;
use yzh52521\auth\guard\Token;
use yzh52521\auth\interfaces\Guard;
use yzh52521\auth\interfaces\StatefulGuard;

/**
 * Class Auth
 * @package yzh52521
 * @mixin Session
 * @mixin Token
 */
class Auth extends Manager
{
    protected $namespace = '\\yzh52521\\auth\\guard\\';

    protected $default = null;

    public function shouldUse($name)
    {
        $this->default = $name;
        return $this;
    }

    /**
     * @param null $name
     * @return Guard|StatefulGuard|Session|Token
     */
    public function guard($name = null)
    {
        return $this->driver($name);
    }

    /**
     * 获取配置
     * @param null|string $name 名称
     * @param mixed $default 默认值
     * @return mixed
     */
    public function getConfig(string $name = null, $default = null)
    {
        if (!is_null($name)) {
            return $this->app->config->get('auth.' . $name, $default);
        }

        return $this->app->config->get('auth');
    }

    /**
     * 获取guard配置
     * @param string $guard
     * @param string|null $name
     * @param null $default
     * @return mixed
     */
    public function getGuardConfig(string $guard, string $name = null, $default = null)
    {
        if ($config = $this->getConfig("guards.{$guard}")) {
            return Arr::get($config, $name, $default);
        }

        throw new InvalidArgumentException("Guard [$guard] not found.");
    }

    /**
     * 获取provider配置
     * @param string $provider
     * @param string|null $name
     * @param null $default
     * @return mixed
     */
    public function getProviderConfig(string $provider, string $name = null, $default = null)
    {
        if ($config = $this->getConfig("providers.{$provider}")) {
            return Arr::get($config, $name, $default);
        }

        throw new InvalidArgumentException("Provider [$provider] not found.");
    }

    /**
     * 获取驱动类型
     * @param string $name
     * @return mixed
     */
    protected function resolveType(string $name)
    {
        return $this->getGuardConfig($name, 'type');
    }

    /**
     * 获取驱动配置
     * @param string $name
     * @return mixed
     */
    protected function resolveConfig(string $name): mixed
    {
        return $this->getGuardConfig($name);
    }

    protected function resolveParams($name): array
    {
        $config = $this->resolveConfig($name);

        $providerName = $this->getGuardConfig($name, 'provider');

        $provider = $this->createUserProvider($providerName);

        return [$provider, $config];
    }

    public function createUserProvider($provider)
    {
        $config = $this->getProviderConfig($provider);

        $type = Arr::pull($config, 'type');

        $namespace = '\\yzh52521\\auth\\provider\\';

        $class = false !== strpos($type, '\\') ? $type : $namespace . Str::studly($type);

        if (class_exists($class)) {
            return $this->app->invokeClass($class, [$config]);
        }

        throw new InvalidArgumentException("Provider [$type] not supported.");
    }

    /**
     * 默认驱动
     * @return string|null
     */
    public function getDefaultDriver(): ?string
    {
        return $this->default ?? $this->getConfig('default');
    }
}
