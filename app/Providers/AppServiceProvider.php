<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config; // 🛠️ Pastikan ini di-import

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
        // Ambil path URL yang sedang diakses saat ini secara aman
        $path = request()->getPathInfo();

        // Jika URL diakses oleh pengepul atau mitra, pisahkan session cookienya
        if (str_contains($path, '/mitra') || str_contains($path, '/dashboard-pengepul')) {
            Config::set('session.cookie', 'trashify_pengepul_session');
        } else {
            Config::set('session.cookie', 'trashify_user_session');
        }
    }
}