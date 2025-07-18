<?php

declare(strict_types=1);

namespace Webstack\ApiPlatformWorkflowBundle\PathResolver;

use ApiPlatform\PathResolver\OperationPathResolverInterface;

final class OperationPathResolver implements OperationPathResolverInterface
{
    public function __construct(
        private readonly OperationPathResolverInterface $decorated,
    ) {
    }

    public function resolveOperationPath(string $resourceShortName, array $operation, mixed $operationType): string
    {
        $path = $this->decorated->resolveOperationPath($resourceShortName, $operation, $operationType);

        if (!isset($operation['_path_suffix'])) {
            return $path;
        }

        return str_replace('{id}', '{id}'.$operation['_path_suffix'], $path);
    }
}
