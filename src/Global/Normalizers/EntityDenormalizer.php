<?php

namespace App\Global\Normalizers;

use App\Modules\Project\Entity\Project;
use App\Modules\Project\Services\ProjectService;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class EntityDenormalizer implements DenormalizerInterface
{

    public function __construct(
        private readonly ProjectService $projectService,
    )
    {
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Project::class => true,
        ];
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === Project::class;
    }

    public function denormalize($data, $type, $format = null, array $context = []): ?Project
    {
        if (!is_int($data)) {
            throw new \InvalidArgumentException('Invalid project ID.');
        }

        $project = $this->projectService->getProjectById($data);

        if ($project === null) {
            throw new \RuntimeException('Project not found.');
        }

        return $project;
    }

}