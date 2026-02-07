<?php

declare(strict_types=1);

use Eloquage\DockerPhp\Tests\TestCase;

$lawmanAutoload = __DIR__.'/../vendor/jonpurvis/lawman/src/Autoload.php';
if (file_exists($lawmanAutoload)) {
    require_once $lawmanAutoload;
}

uses(TestCase::class)->in('Feature', 'Unit');
