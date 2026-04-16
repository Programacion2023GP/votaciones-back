<?php

namespace App\Providers;

use App\Models\Ballot;
use App\Models\Casilla;
use App\Models\Menu;
use App\Models\Participation;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use App\Observers\GlobalModelObserver;
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
     * Lista EXPLÍCITA de modelos a observar
     * Máximo rendimiento - sin reflection, sin filesystem
     */
    protected $observableModels = [
        Role::class,
        Menu::class,
        Casilla::class,
        User::class,
        Participation::class,
        Project::class,
        Ballot::class
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        foreach ($this->observableModels as $model) {
            $model::observe(GlobalModelObserver::class);
        }
    }
}
