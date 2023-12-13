<?php

namespace App\Providers;

use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\RecipeRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);

        $this->app->bind(RepositoryInterface::class, function (){
            return new RecipeRepository();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
