<?php

/*
 * This file is part of ibrand/laravel-shopping-cart.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Shoppingcart\Storage;

use Cache;

/**
 * Class CacheStorage.
 */
class CacheStorage implements Storage
{
    /**
     * @var int
     */
    protected static $lifetime = 43200;

    /**
     * @param $time
     */
    public static function setMinutesOfLifeTime($time)
    {
        if (is_int($time) && $time > 0) {
            self::$lifetime = $time;
        }
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        if (Cache::has($key)) {
            Cache::forget($key);
            Cache::put($key, $value, self::$lifetime);
        } else {
            Cache::put($key, $value, self::$lifetime);
        }
    }

    /**
     * @param $key
     * @param null $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return Cache::get($key, $default);
    }

    /**
     * @param $key
     */
    public function forget($key)
    {
        if (Cache::has($key)) {
            Cache::forget($key);
        }
    }
}
