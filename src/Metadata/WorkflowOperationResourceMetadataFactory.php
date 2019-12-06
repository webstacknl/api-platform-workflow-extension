<?php

declare(strict_types=1);

namespace Webstack\ApiPlatformWorkflowBundle\Metadata;

use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;
use ApiPlatform\Core\Metadata\Resource\ResourceMetadata;
use Webstack\ApiPlatformWorkflowBundle\Model\WorkflowDTO;

/**
 * Class WorkflowOperationResourceMetadataFactory
 */
final class WorkflowOperationResourceMetadataFactory implements ResourceMetadataFactoryInterface
{
    private $supportsWorkflow;
    private $decorated;

    /**
     * WorkflowOperationResourceMetadataFactory constructor.
     *
     * @param array $supportsWorkflow
     * @param ResourceMetadataFactoryInterface $decorated
     */
    public function __construct(array $supportsWorkflow = [], ResourceMetadataFactoryInterface $decorated)
    {
        $this->supportsWorkflow = $supportsWorkflow;
        $this->decorated = $decorated;
    }

    /**
     * {@inheritdoc}
     */
    public function create(string $resourceClass): ResourceMetadata
    {
        $resourceMetadata = $this->decorated->create($resourceClass);

        if (!in_array($resourceClass, $this->supportsWorkflow, true)) {
            return $resourceMetadata;
        }

        $operations = $resourceMetadata->getItemOperations();

        $operations['state_apply'] = [
            'method' => 'PATCH',
            '_path_suffix' => '/transition',
            'input' => ['class' => WorkflowDTO::class, 'name' => 'WorkflowDTO'],
            'input_formats' => [
                'json' => [
                    'application/merge-patch+json',
                ],
            ],
            'output_formats' => $operations['get']['output_formats'],
            'normalization_context' => $operations['get']['normalization_context'],
        ];

        $operations['state_get'] = [
            'method' => 'GET',
            '_path_suffix' => '/state',
        ];

        return $resourceMetadata
            ->withItemOperations($operations)
        ;
    }
}
