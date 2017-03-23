<?php

namespace Rymanalu\FactoryGenerator;

use Illuminate\Support\ServiceProvider;

class FactoryGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands(Console\MakeCommand::class);
        }
    }
}
