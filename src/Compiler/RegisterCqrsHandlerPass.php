<?php

declare(strict_types=1);

namespace WellConsidered\CqrsMessenger\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use WellConsidered\Cqrs\Command;
use WellConsidered\Cqrs\Exception\MultipleHandlersFound;
use WellConsidered\Cqrs\Query;
use WellConsidered\CqrsMessenger\Attribute\AsCommandHandler;
use WellConsidered\CqrsMessenger\Attribute\AsQueryHandler;

final class RegisterCqrsHandlersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $commandHandlers = [];
        $queryHandlers = [];

        foreach ($container->getDefinitions() as $id => $definition) {
            $class = $definition->getClass();
            if (!$class || !class_exists($class)) {
                continue;
            }

            $ref = new \ReflectionClass($class);

            if ($this->hasAttribute($ref, AsCommandHandler::class)) {
                $messageClass = $this->extractMessageType($ref, Command::class);
                $this->assertSingleHandler($messageClass, $commandHandlers);
                $commandHandlers[$messageClass] = $id;

                $definition->addTag('messenger.message_handler', [
                    'bus' => $container->getParameter('cqrs.command_bus'),
                ]);
            }

            if ($this->hasAttribute($ref, AsQueryHandler::class)) {
                $messageClass = $this->extractMessageType($ref, Query::class);
                $this->assertSingleHandler($messageClass, $queryHandlers);
                $queryHandlers[$messageClass] = $id;

                $definition->addTag('messenger.message_handler', [
                    'bus' => $container->getParameter('cqrs.query_bus'),
                ]);
            }
        }
    }

    private function hasAttribute(\ReflectionClass $ref, string $fqcn): bool
    {
        $attrs = $ref->getAttributes($fqcn);

        return count($attrs) > 0;
    }

    private function extractMessageType(\ReflectionClass $ref, string $class): string
    {
        $parameter = $ref->getMethod('__invoke')->getParameters()[0] ?? null;

        return $parameter->getType()->getName();
    }

    private function assertSingleHandler(string $messageClass, array $queryHandlers): void
    {
        if (isset($queryHandlers[$messageClass]) && $queryHandlers[$messageClass] === []) {
            return;
        }

        throw new MultipleHandlersFound('CQRS violation: multiple handlers found for command: ' . $messageClass);
    }
}
