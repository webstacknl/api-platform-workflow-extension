<?php

declare(strict_types=1);

namespace Webstack\ApiPlatformWorkflowBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Webstack\ApiPlatformWorkflowBundle\DependencyInjection\Compiler\WorkflowPass;

class WebstackApiPlatformWorkflowBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new WorkflowPass());
    }
}
