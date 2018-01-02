<?php
namespace ElementVip\Shoppingcart;

use Illuminate\Support\Collection;
use ElementVip\Shoppingcart\Storage\CacheStorage;
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
            __DIR__ . '/config.php' => config_path('ShoppingCart.php'),
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        // merge configs
        $this->mergeConfigFrom(
            __DIR__ . '/config.php', 'ShoppingCart'
        );

        $this->app->singleton(Cart::class, function ($app) {

            if ($user = request()->user() or $user = auth()->user()) {
                $storage = config('ShoppingCart.storage');
            } else {
                $storage = 'ElementVip\Shoppingcart\Storage\SessionStorage';
            }
            $cart = new Cart(new $storage(), $app['events']);

            if ($storage == 'ElementVip\Shoppingcart\Storage\CacheStorage' OR $storage == 'ElementVip\Shoppingcart\Storage\DatabaseStorage') {
                if ($user) {
                    $cart->name($user->id);
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

    protected function registerMigrations()
    {
        return $this->loadMigrationsFrom(__DIR__ . '/../migrations');
    }
}
