<?php

declare(strict_types=1);

use Eloquage\DockerPhp\Support\NetworkInterfaces;

it('returns an array', function () {
    $result = NetworkInterfaces::getInterfaceNames();

    expect($result)->toBeArray();
});

it('returns only string keys when function is available', function () {
    $result = NetworkInterfaces::getInterfaceNames(true);

    expect($result)->toBeArray();
    foreach ($result as $name) {
        expect($name)->toBeString();
    }
});

it('accepts upOnly parameter', function () {
    $withUp = NetworkInterfaces::getInterfaceNames(true);
    $all = NetworkInterfaces::getInterfaceNames(false);

    expect($withUp)->toBeArray();
    expect($all)->toBeArray();
    expect(count($all))->toBeGreaterThanOrEqual(count($withUp));
});
