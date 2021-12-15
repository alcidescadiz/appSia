<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Http\ViewComposer\TiposPagos;
use App\Http\ViewComposer\DatosProveedores;
use App\Http\ViewComposer\DatosClientes;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        View::composer(['/compras/create', 'compras.update', 'ventas.update', '/ventas/create'], TiposPagos::class);

        View::composer(['/compras/create', 'compras.update'], DatosProveedores::class);

        View::composer(['layouts.app','/ventas/create', 'ventas.update'], DatosClientes::class);

        Paginator::useBootstrap();
    }
}
