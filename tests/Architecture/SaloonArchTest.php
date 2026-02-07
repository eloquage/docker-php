<?php

declare(strict_types=1);

use Eloquage\DockerPhp\Connectors\DockerConnector;
use Eloquage\DockerPhp\Requests\Images\CreateImageRequest;
use Eloquage\DockerPhp\Requests\Images\DeleteImageRequest;
use Eloquage\DockerPhp\Requests\Images\ListImagesRequest;
use Eloquage\DockerPhp\Requests\System\InfoRequest;
use Eloquage\DockerPhp\Requests\System\PingRequest;

test('connector extends Saloon Connector and uses required traits', function () {
    expect(DockerConnector::class)
        ->toBeSaloonConnector()
        ->toUseAcceptsJsonTrait()
        ->toUseAlwaysThrowOnErrorsTrait();
});

test('ping request is a Saloon request and sends GET', function () {
    expect(PingRequest::class)
        ->toBeSaloonRequest()
        ->toSendGetRequest();
});

test('info request is a Saloon request and sends GET', function () {
    expect(InfoRequest::class)
        ->toBeSaloonRequest()
        ->toSendGetRequest();
});

test('list images request is a Saloon request and sends GET', function () {
    expect(ListImagesRequest::class)
        ->toBeSaloonRequest()
        ->toSendGetRequest();
});

test('create image request is a Saloon request and sends POST', function () {
    expect(CreateImageRequest::class)
        ->toBeSaloonRequest()
        ->toSendPostRequest();
});

test('delete image request is a Saloon request and sends DELETE', function () {
    expect(DeleteImageRequest::class)
        ->toBeSaloonRequest()
        ->toSendDeleteRequest();
});
