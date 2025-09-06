<?php

namespace App\Providers;

use App\Models\Passport\Client;
use Carbon\CarbonInterval;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {   
        Passport::useClientModel(Client::class);
        Passport::authorizationView('auth.oauth.authorize');
        Passport::tokensExpireIn(CarbonInterval::days(1));
        Passport::refreshTokensExpireIn(CarbonInterval::days(2));
        Passport::personalAccessTokensExpireIn(CarbonInterval::months(6));
    }
}
