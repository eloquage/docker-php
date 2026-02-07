---
title: DTOs
layout: default
nav_order: 4
parent: API reference
---

# Data Transfer Objects

DTOs are readonly classes in `Eloquage\DockerPhp\DataTransferObjects` with a static `fromArray(array $data)` factory. Requests that implement `createDtoFromResponse()` allow you to call `$response->dto()` or `$response->dtoOrFail()` for typed data.

## Available DTOs

| DTO | Used by | Description |
|-----|---------|-------------|
| **ImageSummary** | ListImagesRequest | Id, RepoTags, RepoDigests, Size, Created, Labels, Containers, Architecture, Os |
| **ImageInspectDto** | InspectImageRequest | Full image inspect payload |
| **ImageHistoryItem** | ImageHistoryRequest | Id, Created, CreatedBy, Size |
| **ImageSearchResult** | SearchImagesRequest | Name, description, star_count, is_official |
| **ContainerSummary** | ListContainersRequest | Container list item fields |
| **NetworkSummary** | ListNetworksRequest | Network list item fields |
| **VolumeSummary** | ListVolumesRequest | Volume list item fields (from `Volumes` array) |
| **SystemInfo** | InfoRequest | Wraps full info JSON; methods: `name()`, `ncpu()`, `memTotal()`, `containers()`, `containersRunning()`, `images()` |
| **SystemVersion** | VersionRequest | Version info |
| **DiskUsage** | SystemDfRequest | System disk usage response |

## Example

```php
$response = DockerPhp::images()->list();
/** @var \Eloquage\DockerPhp\DataTransferObjects\ImageSummary[] $summaries */
$summaries = $response->dto();

$response = DockerPhp::system()->info();
/** @var \Eloquage\DockerPhp\DataTransferObjects\SystemInfo $info */
$info = $response->dto();
$hostname = $info->name();
$cpus = $info->ncpu();
```
