<?php

declare(strict_types=1);

namespace Webstack\ApiPlatformWorkflowBundle\DependencyInjection;

use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class WebstackApiPlatformExtensionsExtension
 */
class WebstackWorkflowExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
//        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
//        $loader->load('services.xml');
    }
}
