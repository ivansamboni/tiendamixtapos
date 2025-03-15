<?php

namespace App\Providers;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

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
        ResetPassword::createUrlUsing(function (User $user, string $token) {
            return 'https://tiendamixta.devsamb.com/auth/resetpassword/'.$token;
        });
        Paginator::useBootstrap();
        
    }
}
