<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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
        View::composer('*', function ($view) {
            $viewData = $view->getData();
            if (!array_key_exists('user', $viewData) || is_null($viewData['user'])) {
                $user = Auth::user();
                if (!$user) {
                    try {
                        $user = User::where('role', 'user')->orWhere('username', 'petugas')->first() ?? User::first();
                    } catch (\Throwable $e) {
                        $user = null;
                    }
                }
                $view->with('user', $user);
            }
        });
    }
}
