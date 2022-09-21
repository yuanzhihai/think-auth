<?php
declare ( strict_types = 1 );

namespace yzh52521\auth\credentials;

use ArrayObject;
use ReflectionClass;

class BaseCredentials extends ArrayObject
{

    /**
     * @param array $credentials
     * @return static
     * @throws \ReflectionException
     */
    public static function fromArray(array $credentials = [])
    {
        $reflect = new ReflectionClass( static::class );

        /** @var static $object */
        $object = $reflect->newInstanceWithoutConstructor();

        $object->exchangeArray( $credentials );

        return $object;
    }

}
