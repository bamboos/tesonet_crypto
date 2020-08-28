<?php

namespace App\Providers;

use App\Service\Converter;
use App\Service\CryptonatorExchanger\CryptonatorAdaptor;
use App\Service\CryptonatorExchanger\CryptonatorProxy;
use App\Service\Exchanger;
use Illuminate\Container\Container;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CryptonatorProxy::class,
            fn (Container $app) => new CryptonatorProxy()
        );

       $this->app->singleton(
           Exchanger::class,
            function (Container $app) {
                $config = $app->make('config')->get('app.cryptonator', []);
                return new CryptonatorAdaptor(
                    $app->make(CryptonatorProxy::class),
                    $config
                );
            }
        );

       $this->app->singleton(
           Converter::class,
           fn (Container $app) => new Converter($app->make(Exchanger::class))
       );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(
            'App\Contracts\Exchanger',
            CryptonatorAdaptor::class
        /*fn (Container $app) => new CryptonatorAdaptor(
            $app->make(CryptonatorProxy::class),
            []//config('riak')//['cryptonator']
        )*/
        );
    }
}
