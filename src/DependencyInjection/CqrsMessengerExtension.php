<?php

declare(strict_types=1);

namespace WellConsidered\CqrsMessenger\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class CqrsMessengerExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('cqrs.command_bus', $config['command_bus']);
        $container->setParameter('cqrs.query_bus', $config['query_bus']);
        $container->setParameter('cqrs.enforce', $config['enforce']);
        $container->setParameter('cqrs.idempotency.enabled', $config['idempotency']['enabled']);

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../../config')
        );

        $loader->load('cqrs.yaml');
    }
}
