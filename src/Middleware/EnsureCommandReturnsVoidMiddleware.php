<?php

declare(strict_types=1);

namespace WellConsidered\CqrsMessenger\Middleware;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use WellConsidered\Cqrs\Exception\CommandReturnedValue;

class EnsureCommandReturnsVoidMiddleware implements MiddlewareInterface
{
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $envelope = $stack->next()->handle($envelope, $stack);

        /** @var HandledStamp $handled */
        $handled = $envelope->last(HandledStamp::class);

        if ($handled && $handled->getResult() !== null) {
            throw new CommandReturnedValue(sprintf(
                'CQRS violation: command handler returned a value (%s). Commands must return void.',
                get_debug_type($handled->getResult())
            ));
        }

        return $envelope;
    }
}