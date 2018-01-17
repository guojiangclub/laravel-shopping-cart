<?php

/*
 * This file is part of ibrand/laravel-shopping-cart.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Illuminate\Session;

use Mockery as m;

class SessionManager
{
    public function __call($method, $args)
    {
        return call_user_func_array([$this->getSession(), $method], $args);
    }

    public function getSession()
    {
        static $store;

        if (is_null($store)) {
            $reflection = new \ReflectionClass('Illuminate\Session\Store');
            $store = $reflection->newInstanceArgs($this->getMocks());
        }

        return $store;
    }

    public function getMocks()
    {
        return [
            $this->getSessionName(),
            m::mock('SessionHandlerInterface'),
            $this->getSessionId(),
        ];
    }

    public function getSessionId()
    {
        return 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
    }

    public function getSessionName()
    {
        return 'name';
    }
}
