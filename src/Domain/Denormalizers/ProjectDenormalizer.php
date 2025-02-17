<?php

namespace App\Global\Denormalizers;

use App\Helpers\HelperAction;
use App\Modules\Project\Entity\Project;
use App\Modules\Project\Services\ProjectService;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ProjectDenormalizer implements DenormalizerInterface
{

    public function __construct(
        private readonly ProjectService $projectService,
        private readonly HelperAction $helperAction
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
        $project = null;
        if (is_int($data)) {
            $project = $this->projectService->getProjectById($data);
            if ($project === null) {
                $this->helperAction->jsonNotFoundOrError('Project not found.');
                return null;
            }
        }

        if (is_array($data)) {
            if ($context['object_to_populate'] instanceof Project) {
                $project = $context['object_to_populate'];
                $project->setName($data['name']);
                $project->setDescription($data['description']);
                return $project;
            }
        }
        return $project;
    }

}