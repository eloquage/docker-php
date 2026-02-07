<?php

declare(strict_types=1);

use Eloquage\DockerPhp\Http\Controllers\ImagePullStreamController;
use Illuminate\Support\Facades\Route;

$prefix = config('docker-php.ui.prefix', 'docker');
$middleware = config('docker-php.ui.middleware', ['web', 'auth']);

Route::middleware($middleware)->prefix($prefix)->group(function () {
    Route::get('/', fn () => view('docker-php::layouts.app', ['livewireComponent' => 'docker-php::dashboard']))->name('docker-php.dashboard');
    Route::get('/swarm', fn () => view('docker-php::layouts.app', ['livewireComponent' => 'docker-php::swarm-manager']))->name('docker-php.swarm.index');
    Route::get('/containers', fn () => view('docker-php::layouts.app', ['livewireComponent' => 'docker-php::container-list']))->name('docker-php.containers.index');
    Route::get('/containers/{id}', fn () => view('docker-php::layouts.app', ['livewireComponent' => 'docker-php::container-inspect', 'livewireParams' => ['id' => request()->route('id')]]))->name('docker-php.containers.show');
    Route::get('/containers/{id}/logs', fn () => view('docker-php::layouts.app', ['livewireComponent' => 'docker-php::container-logs', 'livewireParams' => ['id' => request()->route('id')]]))->name('docker-php.containers.logs');
    Route::get('/images', fn () => view('docker-php::layouts.app', ['livewireComponent' => 'docker-php::image-list']))->name('docker-php.images.index');
    Route::post('/images/pull-stream', ImagePullStreamController::class)->name('docker-php.images.pull-stream');
    Route::get('/images/{name}', fn () => view('docker-php::layouts.app', ['livewireComponent' => 'docker-php::image-inspect', 'livewireParams' => ['name' => request()->route('name')]]))->name('docker-php.images.show')->where('name', '.+');
    Route::get('/volumes', fn () => view('docker-php::layouts.app', ['livewireComponent' => 'docker-php::volume-list']))->name('docker-php.volumes.index');
    Route::get('/networks', fn () => view('docker-php::layouts.app', ['livewireComponent' => 'docker-php::network-list']))->name('docker-php.networks.index');
    Route::get('/services', fn () => view('docker-php::layouts.app', ['livewireComponent' => 'docker-php::service-list']))->name('docker-php.services.index');
    Route::get('/nodes', fn () => view('docker-php::layouts.app', ['livewireComponent' => 'docker-php::node-list']))->name('docker-php.nodes.index');
    Route::get('/tasks', fn () => view('docker-php::layouts.app', ['livewireComponent' => 'docker-php::task-list']))->name('docker-php.tasks.index');
    Route::get('/secrets', fn () => view('docker-php::layouts.app', ['livewireComponent' => 'docker-php::secret-list']))->name('docker-php.secrets.index');
    Route::get('/configs', fn () => view('docker-php::layouts.app', ['livewireComponent' => 'docker-php::config-list']))->name('docker-php.configs.index');
    Route::get('/plugins', fn () => view('docker-php::layouts.app', ['livewireComponent' => 'docker-php::plugin-list']))->name('docker-php.plugins.index');
});
