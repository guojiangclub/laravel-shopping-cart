<?php

namespace ElementVip\Shoppingcart\Storage;

use Cache;

class CacheStorage implements Storage
{
    protected static $lifetime = 43200;

    public static function setMinutesOfLifeTime($time)
    {
        if (is_int($time) && $time > 0) {
            self::$lifetime = $time;
        }
    }

    public function set($key, $value)
    {
        if (Cache::has($key)) {
            Cache::forget($key);
            Cache::put($key, $value, self::$lifetime);
        }else{
            Cache::put($key, $value, self::$lifetime);
        }
    }

    public function get($key, $default = null)
    {
        return Cache::get($key, $default);
    }

    public function forget($key)
    {
        if (Cache::has($key)) {
            Cache::forget($key);
        }
    }
}
