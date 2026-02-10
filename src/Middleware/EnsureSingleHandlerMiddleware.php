<?php

declare(strict_types=1);

namespace WellConsidered\CqrsMessenger\Middleware;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use WellConsidered\Cqrs\Exception\MultipleHandlersFound;
use WellConsidered\Cqrs\Exception\NoHandlerFound;

class EnsureSingleHandlerMiddleware implements MiddlewareInterface
{
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $envelope = $stack->next()->handle($envelope, $stack);

        $handled = $envelope->all(HandledStamp::class);

        $count = count($handled);

        if ($count === 0) {
            throw new NoHandlerFound(sprintf('CQRS violation: no message handler found for command %s', get_debug_type($envelope->getMessage())));
        } elseif ($count > 1) {
            throw new MultipleHandlersFound(sprintf('CQRS violation: multiple handlers found for command %s', get_debug_type($envelope->getMessage())));
        }

        return $envelope;
    }
}
