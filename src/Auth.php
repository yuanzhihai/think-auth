<?php

namespace yzh52521;

use InvalidArgumentException;
use think\helper\Arr;
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

    protected $customProviderCreators = [];

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
        return $this->driver( $name );
    }

    /**
     * 获取配置
     * @param null|string $name 名称
     * @param mixed $default 默认值
     * @return mixed
     */
    public function getConfig(string $name = null,$default = null)
    {
        if (!is_null( $name )) {
            return $this->app->config->get( 'auth.'.$name,$default );
        }

        return $this->app->config->get( 'auth' );
    }

    /**
     * 获取guard配置
     * @param string $guard
     * @param string|null $name
     * @param null $default
     * @return mixed
     */
    public function getGuardConfig(string $guard,string $name = null,$default = null)
    {
        if ($config = $this->getConfig( "guards.{$guard}" )) {
            return Arr::get( $config,$name,$default );
        }

        throw new InvalidArgumentException( "Guard [$guard] not found." );
    }

    /**
     * 获取provider配置
     * @param string $provider
     * @param string|null $name
     * @param null $default
     * @return mixed
     */
    public function getProviderConfig(string $provider,string $name = null,$default = null)
    {
        if ($config = $this->getConfig( "providers.{$provider}" )) {
            return Arr::get( $config,$name,$default );
        }

        throw new InvalidArgumentException( "Provider [$provider] not found." );
    }

    /**
     * 获取驱动类型
     * @param string $name
     * @return mixed
     */
    protected function resolveType(string $name)
    {
        return $this->getGuardConfig( $name,'driver' );
    }


    /**
     * 获取驱动配置
     * @param string $name
     * @return mixed
     */
    protected function resolveConfig(string $name): mixed
    {
        return $this->getGuardConfig( $name );
    }

    protected function resolveParams($name): array
    {
        $config = $this->resolveConfig( $name );


        $providerName = $this->getGuardConfig( $name,'provider' );

        $provider = $this->createUserProvider( $providerName );

        return [$provider,$config];
    }

    public function createUserProvider($provider)
    {
        if (is_null( $config = $this->getProviderConfig( $provider ) )) {
            return;
        }

        if (isset( $this->customProviderCreators[$driver = ( $config['driver'] ?? null )] )) {
            return call_user_func(
                $this->customProviderCreators[$driver],$this->app,$config
            );
        }

        $class = str_contains( $driver,'\\' ) ? $driver : '\\yzh52521\\auth\\provider\\ModelUserProvider';

        if (class_exists( $class )) {
            return $this->app->invokeClass( $class,[$config] );
        }

        throw new InvalidArgumentException( "Provider [$driver] not supported." );
    }

    /**
     * 默认驱动
     * @return string|null
     */
    public function getDefaultDriver(): ?string
    {
        return $this->default ?? $this->getConfig( 'default' );
    }
}
