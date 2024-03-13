<?php

declare(strict_types=1);

namespace Webstack\ApiPlatformWorkflowBundle\Metadata;

use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;
use ApiPlatform\Core\Metadata\Resource\ResourceMetadata;
use Webstack\ApiPlatformWorkflowBundle\Model\WorkflowDTO;

final readonly class WorkflowOperationResourceMetadataFactory implements ResourceMetadataFactoryInterface
{
    public function __construct(
        private array $supportsWorkflow,
        private ResourceMetadataFactoryInterface $decorated,
    ) {
    }

    public function create(string $resourceClass): ResourceMetadata
    {
        $resourceMetadata = $this->decorated->create($resourceClass);

        if (!in_array($resourceClass, $this->supportsWorkflow, true)) {
            return $resourceMetadata;
        }

        $operations = $resourceMetadata->getItemOperations();

        if (!$operations || !array_key_exists('get', $operations)) {
            return $resourceMetadata;
        }

        $operations['state_apply'] = [
            'method' => 'PATCH',
            'stateless' => true,
            '_path_suffix' => '/transition',
            'input' => ['class' => WorkflowDTO::class, 'name' => 'WorkflowDTO'],
            'input_formats' => [
                'json' => [
                    'application/merge-patch+json',
                ],
            ],
            'output_formats' => $operations['get']['output_formats'],
            'normalization_context' => $operations['get']['normalization_context'],
            'validation_groups' => ['workflow'],
        ];

        $operations['state_get'] = [
            'method' => 'GET',
            'stateless' => true,
            '_path_suffix' => '/state',
        ];

        return $resourceMetadata
            ->withItemOperations($operations);
    }
}
