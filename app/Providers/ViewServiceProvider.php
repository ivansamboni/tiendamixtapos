<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Categoria;
use App\Models\Marca;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $categorias = Categoria::orderBy("nombre", "asc")->get();
        $marcas = Marca::orderBy("nombre", "asc")->get();//

        View::share('categorias', $categorias);
        View::share('marcas', $marcas);
    }
}
