<?php

declare(strict_types=1);

namespace Eloquage\DockerPhp\DataTransferObjects;

/**
 * @phpstan-type ContainerSummaryArray array{Id?: string, Names?: array<int, string>, Image?: string, ImageID?: string, Command?: string, Created?: int, Ports?: array<int, array<string, mixed>>, SizeRw?: int, SizeRootFs?: int, Labels?: array<string, string>, State?: string, Status?: string}
 */
final readonly class ContainerSummary
{
    public function __construct(
        public string $id,
        public array $names,
        public string $image,
        public string $command,
        public int $created,
        public array $ports,
        public string $state,
        public string $status,
        public ?string $imageId = null,
        public ?int $sizeRw = null,
        public ?int $sizeRootFs = null,
        public array $labels = [],
    ) {}

    /**
     * @param  ContainerSummaryArray  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['Id'] ?? '',
            names: $data['Names'] ?? [],
            image: $data['Image'] ?? '',
            command: $data['Command'] ?? '',
            created: (int) ($data['Created'] ?? 0),
            ports: $data['Ports'] ?? [],
            state: $data['State'] ?? '',
            status: $data['Status'] ?? '',
            imageId: isset($data['ImageID']) ? (string) $data['ImageID'] : null,
            sizeRw: isset($data['SizeRw']) ? (int) $data['SizeRw'] : null,
            sizeRootFs: isset($data['SizeRootFs']) ? (int) $data['SizeRootFs'] : null,
            labels: $data['Labels'] ?? [],
        );
    }
}
