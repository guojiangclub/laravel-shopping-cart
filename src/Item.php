<?php

/*
 * This file is part of ibrand/laravel-shopping-cart.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Shoppingcart;

use Illuminate\Support\Collection;

/**
 * Shopping cart item.
 *
 * @property int|string $id
 * @property string     $__raw_id
 */
class Item extends Collection
{
    /**
     * The Eloquent model a cart is associated with.
     *
     * @var string
     */
    protected $model;

    /**
     * Magic accessor.
     *
     * @param string $property property name
     *
     * @return mixed
     */
    public function __get($property)
    {
        if ($this->has($property)) {
            return $this->get($property);
        }

        if (!$this->get('__model')) {
            return;
        }

        $model = $this->get('__model');
        $class = explode('\\', $model);

        if (strtolower(end($class)) === $property or 'model' === $property) {
            $model = new $model();

            return $model->find($this->id);
        }

        return;
    }

    /**
     * Return the raw ID of item.
     *
     * @return string
     */
    public function rawId()
    {
        return $this->__raw_id;
    }

    /**
     * @param mixed $items
     *
     * @return static
     */
    public function intersect($items)
    {
        $this->forget('dynamic_sku');

        return new static(array_intersect($this->items, $this->getArrayableItems($items)));
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->rawId();
    }
}
