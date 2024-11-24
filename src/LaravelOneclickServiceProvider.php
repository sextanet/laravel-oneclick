<?php

namespace SextaNet\LaravelOneclick;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use SextaNet\LaravelOneclick\Commands\LaravelOneclickCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelOneclickServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-oneclick')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations([
                'create_oneclick_cards_table',
                'create_oneclick_transactions_table',
            ])
            ->hasCommand(LaravelOneclickCommand::class);
    }

    protected function registerRoutes(...$files): void
    {
        foreach ($files as $file) {
            $this->loadRoutesFrom(__DIR__."/routes/{$file}");
        }
    }

    protected function registerBladeComponents()
    {
        Blade::component('oneclick::partials.debug', 'oneclick-debug');
    }

    public function packageRegistered()
    {
        $this->registerBladeComponents();

        Route::prefix(config('oneclick.prefix'))
            ->name(config('oneclick.name'))
            ->middleware('web')
            ->group(function () {
                $this->registerRoutes('web.php');
            });
    }
}
