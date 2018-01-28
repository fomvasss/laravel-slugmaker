<?php

namespace Fomvasss\SlugMaker;

use Fomvasss\SlugMaker\Models\Slug;
use Illuminate\Support\ServiceProvider;

class SlugMakerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/slugmaker.php' => $this->app->configPath().'/slugmaker.php',
        ], 'slugmaker-config');

        if (! class_exists('CreateSlugsTable')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/../database/migrations/create_slugs_table.php.stub' => $this->app->databasePath()."/migrations/{$timestamp}_create_slugs_table.php",
            ], 'slugmaker-migrations');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/slugmaker.php', 'slugmaker');

        $this->app->bind(SlugHelper::class, function () {
            return new SlugHelper(new Slug());
        });
    }
}
