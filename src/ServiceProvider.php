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

use iBrand\Shoppingcart\Storage\CacheStorage;
use iBrand\Shoppingcart\Storage\DatabaseStorage;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

/**
 * Service provider for Laravel.
 */
class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Boot the provider.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerMigrations();
        }
        //
        //publish a config file
        $this->publishes([
            __DIR__ . '/config.php' => config_path('ibrand/cart.php'),
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        // merge configs
        $this->mergeConfigFrom(
            __DIR__ . '/config.php', 'ibrand.cart'
        );

        $this->app->singleton(Cart::class, function ($app) {

            $currentGuard = null;

            $guards = array_keys(config('auth.guards'));

            foreach ($guards as $guard) {
                if ($user = auth($guard)->user()) {
                    $currentGuard = $guard;
                    break;
                }
            }

            $storage = config('ibrand.cart.storage');

            $cart = new Cart(new $storage(), $app['events']);

            if (CacheStorage::class == $storage or DatabaseStorage::class == $storage) {
                if ($user) {
                    $cart->name($currentGuard . $user->id);
                    $cart->saveFromSession();
                }
            }

            return $cart;
        });

        $this->app->alias(Cart::class, 'cart');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Cart::class, 'cart'];
    }

    /**
     * load migration files.
     */
    protected function registerMigrations()
    {
        return $this->loadMigrationsFrom(__DIR__ . '/../migrations');
    }
}
