<?php

declare(strict_types=1);

namespace Webstack\ApiPlatformWorkflowBundle\PathResolver;

use ApiPlatform\Core\PathResolver\OperationPathResolverInterface;

/**
 * Class OperationPathResolver
 */
final class OperationPathResolver implements OperationPathResolverInterface
{
    /**
     * @var OperationPathResolverInterface
     */
    private $decorated;

    /**
     * OperationPathResolver constructor.
     *
     * @param OperationPathResolverInterface $decorated
     */
    public function __construct(OperationPathResolverInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    /**
     * @param string $resourceShortName
     * @param array $operation
     * @param bool|string $operationType
     *
     * @return string
     */
    public function resolveOperationPath(string $resourceShortName, array $operation, $operationType): string
    {
        $path = $this->decorated->resolveOperationPath($resourceShortName, $operation, $operationType);

        if (!isset($operation['_path_suffix'])) {
            return $path;
        }

        return str_replace('{id}', '{id}'.$operation['_path_suffix'], $path);
    }
}
