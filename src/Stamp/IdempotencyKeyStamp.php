<?php

declare(strict_types=1);

namespace WellConsidered\CqrsMessenger\Stamp;

use Symfony\Component\Messenger\Stamp\StampInterface;

final class IdempotencyKeyStamp implements StampInterface
{
    public function __construct(
        public readonly string $key
    ) {}
}
