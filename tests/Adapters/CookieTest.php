<?php

namespace Tests\Adapters;

/**
 *   It is a Cookie adapter for tests
 */
class CookieTest
{
    public static $adapter;

    public static function get($name)
    {
        if (array_key_exists($name, self::$adapter)) {
            return self::$adapter[$name];
        }

        return null;
    }

    public static function set($name, $value)
    {
        self::$adapter[$name] = $value;
    }

    public static function forget($name)
    {
        self::$adapter[$name] = '';
    }

}
