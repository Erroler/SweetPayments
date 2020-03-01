<?php

namespace App\Providers;

use Steam\Steam;
use GuzzleHttp\Client;
use Steam\Configuration;
use Steam\Runner\GuzzleRunner;
use Steam\Utility\GuzzleUrlBuilder;
use Illuminate\Support\ServiceProvider;
use Steam\Runner\DecodeJsonStringRunner;

class SteamProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Steam::class, function ($app) {
            $steam = new Steam(new Configuration([
                Configuration::STEAM_KEY => env('STEAM_API_KEY')
            ]));
            $steam->addRunner(new GuzzleRunner(new Client(), new GuzzleUrlBuilder()));
            $steam->addRunner(new DecodeJsonStringRunner());
            return $steam;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
