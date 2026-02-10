<?php

declare(strict_types=1);

namespace WellConsidered\CqrsMessenger;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use WellConsidered\CqrsMessenger\Compiler\RegisterCqrsHandlersPass;

final class CqrsMessengerBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterCqrsHandlersPass());
    }
}
