<?php

namespace SextaNet\LaravelOneclick;

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
            ->hasMigration('create_laravel_oneclick_table')
            ->hasCommand(LaravelOneclickCommand::class);
    }
}
