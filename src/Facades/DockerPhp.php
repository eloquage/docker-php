<?php

namespace Eloquage\DockerPhp\Facades;

use Illuminate\Support\Facades\Facade;

class DockerPhp extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'docker-php';
    }
}
