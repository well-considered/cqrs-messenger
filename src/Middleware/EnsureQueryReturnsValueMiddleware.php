<?php

declare(strict_types=1);

namespace WellConsidered\CqrsMessenger\Middleware;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use WellConsidered\Cqrs\Exception\QueryReturnedNull;

final class EnsureQueryReturnsValueMiddleware implements MiddlewareInterface
{
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $envelope = $stack->next()->handle($envelope, $stack);

        /** @var HandledStamp $handled */
        $handled = $envelope->last(HandledStamp::class);

        if ($handled === null || $handled->getResult() === null) {
            throw new QueryReturnedNull(
                'CQRS violation: query handler must return a value.'
            );
        }

        return $envelope;
    }
}
