<?php

namespace ElementVip\Shoppingcart\Storage;

interface Storage
{
    public function set($key, $value);

    public function get($key, $default=null);

    public function forget($key);
}
