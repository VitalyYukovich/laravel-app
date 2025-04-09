<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Регистрация сервис-провайдера.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Настройка маршрутов приложения.
     *
     * @return void
     */
    public function boot()
    {
        $this->mapApiRoutes();
        // Другие методы для настройки маршрутов, если необходимо
    }

    /**
     * Определение маршрутов для API.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->group(base_path('routes/api.php'));
    }
}
