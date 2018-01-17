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

/**
 * Class SessionStorage.
 */
class SessionStorage implements Storage
{
    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        session([
            $key => $value,
        ]);
    }

    /**
     * @param $key
     * @param null $default
     *
     * @return \Illuminate\Session\SessionManager|\Illuminate\Session\Store|mixed
     */
    public function get($key, $default = null)
    {
        return session($key, $default);
    }

    /**
     * @param $key
     */
    public function forget($key)
    {
        session()->forget($key);
    }
}
