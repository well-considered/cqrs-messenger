<?php

declare(strict_types=1);

namespace WellConsidered\CqrsMessenger\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class AsCommandHandler
{
}
