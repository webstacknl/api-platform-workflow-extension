<?php

declare(strict_types=1);

namespace Webstack\ApiPlatformWorkflowBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Webstack\ApiPlatformWorkflowBundle\Metadata\WorkflowOperationResourceMetadataFactory;

class WorkflowPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('workflow.registry')) {
            return;
        }

        $registry = $container->getDefinition('workflow.registry');

        if (!$container->hasDefinition(WorkflowOperationResourceMetadataFactory::class)) {
            return;
        }

        $factory = $container->getDefinition(WorkflowOperationResourceMetadataFactory::class);
        $arguments = [];

        foreach ($registry->getMethodCalls() as $methodCall) {
            $supportsStrategy = $methodCall[1][1];

            $arguments[] = $supportsStrategy->getArguments()[0];
        }

        $factory->setArgument(0, $arguments);
    }
}
