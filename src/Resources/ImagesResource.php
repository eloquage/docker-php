<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\Resources;

use Eloquage\DockerPhp\Requests\Images\CreateImageRequest;
use Eloquage\DockerPhp\Requests\Images\DeleteImageRequest;
use Eloquage\DockerPhp\Requests\Images\GetImageRequest;
use Eloquage\DockerPhp\Requests\Images\ImageHistoryRequest;
use Eloquage\DockerPhp\Requests\Images\InspectImageRequest;
use Eloquage\DockerPhp\Requests\Images\ListImagesRequest;
use Eloquage\DockerPhp\Requests\Images\LoadImagesRequest;
use Eloquage\DockerPhp\Requests\Images\PruneImagesRequest;
use Eloquage\DockerPhp\Requests\Images\PushImageRequest;
use Eloquage\DockerPhp\Requests\Images\SearchImagesRequest;
use Eloquage\DockerPhp\Requests\Images\TagImageRequest;
use Saloon\Http\Response;

class ImagesResource extends BaseResource
{
    public function list(?array $filters = null, ?bool $all = null, ?bool $digests = null, ?bool $sharedSize = null, ?bool $manifests = null): Response
    {
        return $this->connector->send(new ListImagesRequest($filters, $all, $digests, $sharedSize, $manifests));
    }

    public function create(?string $fromImage = null, ?string $fromSrc = null, ?string $repo = null, ?string $tag = null, ?string $message = null, ?string $platform = null, ?array $changes = null, ?string $xRegistryAuth = null): Response
    {
        return $this->connector->send(new CreateImageRequest($fromImage, $fromSrc, $repo, $tag, $message, $platform, $changes, $xRegistryAuth));
    }

    public function search(?string $term = null, ?int $limit = null, ?array $filters = null): Response
    {
        return $this->connector->send(new SearchImagesRequest($term, $limit, $filters));
    }

    public function inspect(string $name, ?bool $manifests = null): Response
    {
        return $this->connector->send(new InspectImageRequest($name, $manifests));
    }

    public function history(string $name, ?string $platform = null): Response
    {
        return $this->connector->send(new ImageHistoryRequest($name, $platform));
    }

    public function get(string $name, ?array $platform = null): Response
    {
        return $this->connector->send(new GetImageRequest($name, $platform));
    }

    public function tag(string $name, ?string $repo = null, ?string $tag = null): Response
    {
        return $this->connector->send(new TagImageRequest($name, $repo, $tag));
    }

    public function push(string $name, ?string $tag = null, ?string $platform = null, ?string $xRegistryAuth = null): Response
    {
        return $this->connector->send(new PushImageRequest($name, $tag, $platform, $xRegistryAuth));
    }

    public function delete(string $name, ?bool $force = null, ?bool $noprune = null, ?array $platforms = null): Response
    {
        return $this->connector->send(new DeleteImageRequest($name, $force, $noprune, $platforms));
    }

    public function load(mixed $body = null, ?bool $quiet = null, ?array $platform = null): Response
    {
        return $this->connector->send(new LoadImagesRequest($body, $quiet, $platform));
    }

    public function prune(?array $filters = null): Response
    {
        return $this->connector->send(new PruneImagesRequest($filters));
    }
}
