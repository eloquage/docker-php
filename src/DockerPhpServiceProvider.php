<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp;

use Eloquage\DockerPhp\Connectors\DockerConnector;
use Eloquage\DockerPhp\DockerPhp;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class DockerPhpServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'docker-php');

        if (config('docker-php.ui.enabled', true)) {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
            $this->registerLivewireComponents();
            $this->registerViewComposers();
        }

        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/docker-php.php', 'docker-php');

        $this->app->singleton(DockerConnector::class, function ($app) {
            return new DockerConnector(config('docker-php', []));
        });

        $this->app->singleton('docker-php', function ($app) {
            return new DockerPhp($app->make(DockerConnector::class));
        });
    }

    public function provides(): array
    {
        return ['docker-php', DockerConnector::class];
    }

    protected function registerLivewireComponents(): void
    {
        Livewire::addNamespace(
            'docker-php',
            __DIR__.'/../resources/views/livewire',
            'Eloquage\DockerPhp\Livewire'
        );
    }

    protected function registerViewComposers(): void
    {
        $this->app['view']->composer('docker-php::layouts.app', function ($view): void {
            $swarmInitialized = false;
            try {
                $docker = $this->app->make(DockerPhp::class);
                $response = $docker->swarm()->get();
                $data = $response->successful() ? $response->json() : [];
                $swarmInitialized = is_array($data) && isset($data['ID']);
            } catch (\Throwable) {
                // Leave false when Docker is unreachable
            }
            $view->with('swarmInitialized', $swarmInitialized);
        });
    }

    protected function bootForConsole(): void
    {
        $this->publishes([
            __DIR__.'/../config/docker-php.php' => config_path('docker-php.php'),
        ], 'docker-php.config');

        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/docker-php'),
        ], 'docker-php.views');
    }
}
