<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Support;

use Psr\Http\Message\StreamInterface;

/**
 * Decode Docker's multiplexed stream format (e.g. logs, attach).
 * Frame: 1 byte stream type (0=stdin, 1=stdout, 2=stderr), 3 bytes padding, 4 bytes size (big-endian), then payload.
 */
class StreamDecoder
{
    public const STREAM_STDIN = 0;

    public const STREAM_STDOUT = 1;

    public const STREAM_STDERR = 2;

    /**
     * Decode frames from a stream into an array of [streamType, payload] pairs.
     *
     * @return array<int, array{0: int, 1: string}>
     */
    public static function decodeFrames(StreamInterface $stream): array
    {
        $frames = [];
        while (! $stream->eof()) {
            $header = $stream->read(8);
            if (strlen($header) < 8) {
                break;
            }
            $streamType = ord($header[0]);
            $size = unpack('N', substr($header, 4, 4))[1];
            if ($size > 0) {
                $payload = $stream->read($size);
                $frames[] = [$streamType, $payload];
            }
        }

        return $frames;
    }

    /**
     * Decode frames and return concatenated stdout then stderr as plain strings.
     *
     * @return array{stdout: string, stderr: string}
     */
    public static function decodeToStdoutStderr(StreamInterface $stream): array
    {
        $stdout = '';
        $stderr = '';
        foreach (self::decodeFrames($stream) as [$streamType, $payload]) {
            if ($streamType === self::STREAM_STDOUT) {
                $stdout .= $payload;
            } elseif ($streamType === self::STREAM_STDERR) {
                $stderr .= $payload;
            }
        }

        return ['stdout' => $stdout, 'stderr' => $stderr];
    }
}
