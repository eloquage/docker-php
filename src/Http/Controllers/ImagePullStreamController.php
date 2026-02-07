<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Http\Controllers;

use Eloquage\DockerPhp\Connectors\DockerConnector;
use Eloquage\DockerPhp\Requests\Images\CreateImageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class ImagePullStreamController
{
    public function __invoke(Request $request): mixed
    {
        $validated = $request->validate([
            'fromImage' => ['required', 'string', 'max:500'],
            'tag' => ['nullable', 'string', 'max:128'],
            'platform' => ['nullable', 'string', 'max:128'],
            'xRegistryAuth' => ['nullable', 'string'],
        ]);

        $fromImage = $validated['fromImage'];
        $tag = $validated['tag'] ?? 'latest';
        $platform = $validated['platform'] ?? null;
        $xRegistryAuth = $validated['xRegistryAuth'] ?? null;

        $config = array_merge(Config::get('docker-php', []), [
            'timeout' => 0,
            'connect_timeout' => 30,
        ]);
        $connector = app()->bound('docker-php.pull-stream.connector')
            ? app('docker-php.pull-stream.connector')
            : new DockerConnector($config);

        try {
            $saloonResponse = $connector->send(new CreateImageRequest(
                fromImage: $fromImage,
                fromSrc: null,
                repo: null,
                tag: $tag,
                message: null,
                platform: $platform,
                changes: null,
                xRegistryAuth: $xRegistryAuth,
            ));
        } catch (\Throwable $e) {
            return response(
                "data: ".json_encode(['error' => $e->getMessage()])."\n\n",
                200,
                [
                    'Content-Type' => 'text/event-stream',
                    'Cache-Control' => 'no-cache',
                    'Connection' => 'keep-alive',
                    'X-Accel-Buffering' => 'no',
                ]
            );
        }

        $stream = $saloonResponse->stream();

        $headers = [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ];

        return response()->stream(function () use ($stream): void {
            $buffer = '';
            while (! $stream->eof()) {
                $chunk = $stream->read(8192);
                if ($chunk === '') {
                    continue;
                }
                $buffer .= $chunk;
                while (($pos = strpos($buffer, "\n")) !== false) {
                    $line = substr($buffer, 0, $pos);
                    $buffer = substr($buffer, $pos + 1);
                    $line = trim($line);
                    if ($line !== '') {
                        echo "data: {$line}\n\n";
                        if (ob_get_level()) {
                            ob_flush();
                        }
                        flush();
                    }
                }
            }
            if ($buffer !== '') {
                $line = trim($buffer);
                if ($line !== '') {
                    echo "data: {$line}\n\n";
                }
            }
            echo "data: ".json_encode(['status' => 'complete'])."\n\n";
            if (ob_get_level()) {
                ob_flush();
            }
            flush();
        }, 200, $headers);
    }
}
