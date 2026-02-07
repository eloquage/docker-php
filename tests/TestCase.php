<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Tests;

use Eloquage\DockerPhp\DockerPhpServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
        $app['config']->set('docker-php.ui.middleware', ['web']);
    }

    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            DockerPhpServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'DockerPhp' => \Eloquage\DockerPhp\Facades\DockerPhp::class,
        ];
    }
}
