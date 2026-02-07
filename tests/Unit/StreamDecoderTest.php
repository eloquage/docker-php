<?php

declare(strict_types=1);

use Eloquage\DockerPhp\Support\StreamDecoder;
use GuzzleHttp\Psr7\Utils;

it('decodes empty stream to empty frames', function () {
    $stream = Utils::streamFor('');
    $frames = StreamDecoder::decodeFrames($stream);
    expect($frames)->toBeArray()->toBeEmpty();
});

it('decodes multiplexed frames to stdout and stderr', function () {
    $stdoutPayload = "hello\n";
    $stderrPayload = "error\n";
    $headerStdout = "\x01\x00\x00\x00".pack('N', strlen($stdoutPayload));
    $headerStderr = "\x02\x00\x00\x00".pack('N', strlen($stderrPayload));
    $body = $headerStdout.$stdoutPayload.$headerStderr.$stderrPayload;
    $stream = Utils::streamFor($body);

    $result = StreamDecoder::decodeToStdoutStderr($stream);
    expect($result)->toBe(['stdout' => $stdoutPayload, 'stderr' => $stderrPayload]);
});

it('decodes frames array with stream types', function () {
    $payload = 'data';
    $header = "\x01\x00\x00\x00".pack('N', strlen($payload));
    $stream = Utils::streamFor($header.$payload);

    $frames = StreamDecoder::decodeFrames($stream);
    expect($frames)->toHaveCount(1);
    expect($frames[0][0])->toBe(StreamDecoder::STREAM_STDOUT);
    expect($frames[0][1])->toBe($payload);
});

it('ignores stdin stream type in decodeToStdoutStderr', function () {
    $stdinPayload = "ignored\n";
    $stdoutPayload = "out\n";
    $headerStdin = "\x00\x00\x00\x00".pack('N', strlen($stdinPayload));
    $headerStdout = "\x01\x00\x00\x00".pack('N', strlen($stdoutPayload));
    $body = $headerStdin.$stdinPayload.$headerStdout.$stdoutPayload;
    $stream = Utils::streamFor($body);

    $result = StreamDecoder::decodeToStdoutStderr($stream);
    expect($result)->toBe(['stdout' => $stdoutPayload, 'stderr' => '']);
});

it('stops at partial header when stream ends mid-frame', function () {
    $partialHeader = "\x01\x00\x00";
    $stream = Utils::streamFor($partialHeader);

    $frames = StreamDecoder::decodeFrames($stream);
    expect($frames)->toBeArray()->toBeEmpty();
});

it('decodes multiple frames of same type', function () {
    $a = 'a';
    $b = 'b';
    $ha = "\x01\x00\x00\x00".pack('N', strlen($a));
    $hb = "\x01\x00\x00\x00".pack('N', strlen($b));
    $stream = Utils::streamFor($ha.$a.$hb.$b);

    $result = StreamDecoder::decodeToStdoutStderr($stream);
    expect($result)->toBe(['stdout' => 'ab', 'stderr' => '']);
});

it('decodes stderr stream type', function () {
    $payload = 'error message';
    $header = "\x02\x00\x00\x00".pack('N', strlen($payload));
    $stream = Utils::streamFor($header.$payload);

    $frames = StreamDecoder::decodeFrames($stream);
    expect($frames)->toHaveCount(1);
    expect($frames[0][0])->toBe(StreamDecoder::STREAM_STDERR);
    expect($frames[0][1])->toBe($payload);
});
