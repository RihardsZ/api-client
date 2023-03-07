<?php

namespace Elektrum\SoapClient;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Elektrum\SoapClient\Commands\SoapClientCommand;

class SoapClientServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('soap-client')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_soap-client_table')
            ->hasCommand(SoapClientCommand::class);
    }
}
