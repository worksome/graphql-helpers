<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Definition\Attributes;

use Attribute;
use Closure;
use UnitEnum;

#[Attribute(Attribute::TARGET_CLASS)]
readonly class CasesDescribedByClosure
{
    /** @param Closure(UnitEnum): string $describer */
    public function __construct(public Closure $describer)
    {
    }
}
