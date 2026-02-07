<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp;

use Eloquage\DockerPhp\Connectors\DockerConnector;
use Eloquage\DockerPhp\DockerPhp;
use Eloquage\DockerPhp\Livewire\ConfigList;
use Eloquage\DockerPhp\Livewire\ContainerInspect;
use Eloquage\DockerPhp\Livewire\ContainerList;
use Eloquage\DockerPhp\Livewire\ContainerLogs;
use Eloquage\DockerPhp\Livewire\Dashboard;
use Eloquage\DockerPhp\Livewire\ImageInspect;
use Eloquage\DockerPhp\Livewire\ImageList;
use Eloquage\DockerPhp\Livewire\NetworkList;
use Eloquage\DockerPhp\Livewire\NodeList;
use Eloquage\DockerPhp\Livewire\PluginList;
use Eloquage\DockerPhp\Livewire\SecretList;
use Eloquage\DockerPhp\Livewire\ServiceList;
use Eloquage\DockerPhp\Livewire\SwarmManager;
use Eloquage\DockerPhp\Livewire\TaskList;
use Eloquage\DockerPhp\Livewire\VolumeList;
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
        Livewire::component('docker-php::dashboard', Dashboard::class);
        Livewire::component('docker-php::container-list', ContainerList::class);
        Livewire::component('docker-php::container-inspect', ContainerInspect::class);
        Livewire::component('docker-php::container-logs', ContainerLogs::class);
        Livewire::component('docker-php::image-list', ImageList::class);
        Livewire::component('docker-php::image-inspect', ImageInspect::class);
        Livewire::component('docker-php::volume-list', VolumeList::class);
        Livewire::component('docker-php::network-list', NetworkList::class);
        Livewire::component('docker-php::swarm-manager', SwarmManager::class);
        Livewire::component('docker-php::service-list', ServiceList::class);
        Livewire::component('docker-php::node-list', NodeList::class);
        Livewire::component('docker-php::task-list', TaskList::class);
        Livewire::component('docker-php::secret-list', SecretList::class);
        Livewire::component('docker-php::config-list', ConfigList::class);
        Livewire::component('docker-php::plugin-list', PluginList::class);
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
